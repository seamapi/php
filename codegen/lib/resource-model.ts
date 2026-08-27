// Builds the resource class model for src/Resources from the blueprint.
//
// Nested objects are emitted recursively in the namespace of their owner.
// Discriminated resources and object lists are emitted as an abstract base,
// one final class per variant, and an unknown-discriminant fallback.

import type {
  ActionAttemptStatus,
  Blueprint,
  EnumProperty,
  Property,
  Resource,
} from '@seamapi/blueprint'
import { constantCase, pascalCase } from 'change-case'

import { getPhpDocType, getPhpType } from './map-php-type.js'

export type ResourceClassProperty =
  | ({
      kind: 'value'
      phpType: string
      phpDocType: string
    } & ResourceClassPropertyMetadata)
  | ({
      // The property does not apply to this action attempt status, so it is
      // rendered as the null type.
      kind: 'null'
    } & ResourceClassPropertyMetadata)
  | ({
      kind: 'record'
      phpType: string
      phpDocType: string
    } & ResourceClassPropertyMetadata)
  | ({
      kind: 'objectReference'
      referenceName: string
    } & ResourceClassPropertyMetadata)
  | ({
      kind: 'listReference'
      referenceName: string
    } & ResourceClassPropertyMetadata)

interface ResourceClassPropertyMetadata {
  name: string
  description: string
  isOptional: boolean
  isNullable: boolean
  isDeprecated: boolean
  deprecationMessage: string
}

export interface ResourceFactoryVariant {
  enumCase: string
  className: string
}

export interface ResourceFactory {
  discriminant: string
  enumType: string
  variants: ResourceFactoryVariant[]
}

export interface ResourceClassSchema {
  kind: 'class'
  name: string
  namespace: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
  isFinal: boolean
  extendsName: string
  properties: ResourceClassProperty[]
  inheritedProperties: ResourceClassProperty[]
  factory?: ResourceFactory
}

export interface ResourceEnumSchema {
  kind: 'enum'
  name: string
  namespace: string
  cases: Array<{ name: string; value: string; description: string }>
}

export type ResourceDeclaration = ResourceClassSchema | ResourceEnumSchema

export interface ResourceSchema {
  name: string
  declarations: ResourceDeclaration[]
}

export interface ResourceModel {
  resourceNames: string[]
  resources: ResourceSchema[]
}

const rootNamespace = 'Seam\\Resources'
const maxDepth = 16

const reservedClassNames = new Set([
  'array',
  'bool',
  'callable',
  'enum',
  'false',
  'float',
  'int',
  'iterable',
  'mixed',
  'never',
  'null',
  'object',
  'parent',
  'self',
  'static',
  'string',
  'true',
  'void',
  'abstract',
  'and',
  'as',
  'break',
  'case',
  'catch',
  'class',
  'clone',
  'const',
  'continue',
  'declare',
  'default',
  'die',
  'do',
  'echo',
  'else',
  'elseif',
  'empty',
  'enddeclare',
  'endfor',
  'endforeach',
  'endif',
  'endswitch',
  'endwhile',
  'exit',
  'extends',
  'final',
  'finally',
  'fn',
  'for',
  'foreach',
  'function',
  'global',
  'goto',
  'if',
  'implements',
  'include',
  'include_once',
  'instanceof',
  'insteadof',
  'interface',
  'isset',
  'list',
  'match',
  'namespace',
  'new',
  'or',
  'print',
  'private',
  'protected',
  'public',
  'readonly',
  'require',
  'require_once',
  'return',
  'switch',
  'throw',
  'trait',
  'try',
  'unset',
  'use',
  'var',
  'while',
  'xor',
  'yield',
])

interface ClassDocs {
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

interface BuiltDeclaration {
  declaration: ResourceDeclaration
  nestedDeclarations: BuiltDeclaration[]
}

interface VariantInput {
  properties: Property[]
  description: string
}

export const createResourceModel = (blueprint: Blueprint): ResourceModel => {
  const discriminatedTypes = new Set<string>(
    [...blueprint.events, ...blueprint.actionAttempts].map(
      ({ resourceType }) => resourceType,
    ),
  )
  const resources = new Map(
    blueprint.resources
      .filter(({ resourceType }) => !discriminatedTypes.has(resourceType))
      .map((resource) => [resource.resourceType, resource] as const),
  )

  const resourceTypes: string[] = [
    ...resources.keys(),
    ...(blueprint.events.length > 0 ? ['event'] : []),
    ...(blueprint.actionAttempts.length > 0 ? ['action_attempt'] : []),
  ].sort()

  const schemas = resourceTypes.map((resourceType): ResourceSchema => {
    const name = pascalCase(resourceType)
    let built: BuiltDeclaration

    if (resourceType === 'event') {
      built = buildDiscriminatedClass(
        name,
        rootNamespace,
        blueprint.events,
        'event_type',
        resourceType,
        0,
        {
          description: 'Base class for events returned by the Seam API.',
          isDeprecated: false,
          deprecationMessage: '',
        },
      )
    } else if (resourceType === 'action_attempt') {
      built = buildDiscriminatedClass(
        name,
        rootNamespace,
        blueprint.actionAttempts,
        'action_type',
        resourceType,
        0,
        {
          description:
            'Base class for actions whose completion is tracked asynchronously.',
          isDeprecated: false,
          deprecationMessage: '',
        },
        { isActionAttempt: true },
      )
    } else {
      const resource = resources.get(resourceType)
      built = buildClass(
        name,
        rootNamespace,
        resource?.properties ?? [],
        resourceType,
        0,
        docsFor(resource),
      )
    }

    return { name, declarations: flattenDeclarations(built) }
  })

  return {
    resourceNames: schemas.map(({ name }) => name),
    resources: schemas,
  }
}

const docsFor = (resource: Resource | undefined): ClassDocs => ({
  description: resource?.description ?? '',
  isDeprecated: resource?.isDeprecated ?? false,
  deprecationMessage: resource?.deprecationMessage ?? '',
})

const buildClass = (
  className: string,
  namespace: string,
  classProperties: Property[],
  path: string,
  depth: number,
  docs: ClassDocs,
  options: {
    isFinal?: boolean
    extendsName?: string
    inheritedProperties?: ResourceClassProperty[]
    factory?: ResourceFactory
  } = {},
): BuiltDeclaration => {
  assertDepth(path, depth)

  const nestedNamespace = `${namespace}\\${className}`
  const nestedDeclarations: BuiltDeclaration[] = []
  const takenNames = new Set<string>()

  const properties = classProperties.map((property): ResourceClassProperty => {
    const metadata = propertyMetadata(property)
    const nestedPath = `${path}.${property.name}`
    const nestedClassName = pascalCase(property.name)

    if (isRenderedAsNull(property)) {
      return { ...metadata, kind: 'null' }
    }

    if (property.format === 'enum') {
      assertAvailableName(
        nestedClassName,
        nestedPath,
        nestedNamespace,
        takenNames,
      )
      const enumType = `\\${nestedNamespace}\\${nestedClassName}`
      nestedDeclarations.push(
        buildEnum(nestedClassName, nestedNamespace, property),
      )
      return {
        ...metadata,
        kind: 'value',
        phpType: 'string',
        phpDocType: `value-of<${enumType}>|string`,
      }
    }

    if (
      property.format === 'list' &&
      property.itemFormat === 'discriminated_object'
    ) {
      assertAvailableName(
        nestedClassName,
        nestedPath,
        nestedNamespace,
        takenNames,
      )
      nestedDeclarations.push(
        buildDiscriminatedClass(
          nestedClassName,
          nestedNamespace,
          property.variants,
          property.discriminator,
          nestedPath,
          depth + 1,
          propertyDocs(property),
        ),
      )
      return {
        ...metadata,
        kind: 'listReference',
        referenceName: `\\${nestedNamespace}\\${nestedClassName}`,
      }
    }

    const nestedProperties = getNestedProperties(property)
    if (nestedProperties != null) {
      assertAvailableName(
        nestedClassName,
        nestedPath,
        nestedNamespace,
        takenNames,
      )
      nestedDeclarations.push(
        buildClass(
          nestedClassName,
          nestedNamespace,
          nestedProperties,
          nestedPath,
          depth + 1,
          propertyDocs(property),
        ),
      )
      const referenceName = `\\${nestedNamespace}\\${nestedClassName}`
      return {
        ...metadata,
        kind: property.format === 'list' ? 'listReference' : 'objectReference',
        referenceName,
      }
    }

    return property.format === 'record' && !('resourceType' in property)
      ? {
          ...metadata,
          kind: 'record',
          phpType: getPhpType(property),
          phpDocType: getPhpDocType(property),
        }
      : {
          ...metadata,
          kind: 'value',
          phpType: getPhpType(property),
          phpDocType: getPhpDocType(property),
        }
  })

  return {
    declaration: {
      kind: 'class',
      name: className,
      namespace,
      ...docs,
      isFinal: options.isFinal ?? false,
      extendsName: options.extendsName ?? '',
      properties,
      inheritedProperties: options.inheritedProperties ?? [],
      ...(options.factory == null ? {} : { factory: options.factory }),
    },
    nestedDeclarations,
  }
}

interface DiscriminatedClassOptions {
  // An action attempt variant is further discriminated by status, so each
  // action type gets one subclass per status from its status enum property.
  isActionAttempt?: boolean
  extendsName?: string
  inheritedProperties?: ResourceClassProperty[]
  // A fully qualified enum already declared by an ancestor class to use for
  // the discriminant instead of declaring a new one.
  discriminantEnumType?: string
}

const buildDiscriminatedClass = (
  className: string,
  namespace: string,
  variants: VariantInput[],
  discriminator: string,
  path: string,
  depth: number,
  docs: ClassDocs,
  options: DiscriminatedClassOptions = {},
): BuiltDeclaration => {
  assertDepth(path, depth)
  if (variants.length === 0) {
    return buildClass(className, namespace, [], path, depth, docs)
  }

  const variantInfo = variants.map((variant) => {
    const property = variant.properties.find(
      ({ name }) => name === discriminator,
    )
    if (property?.format !== 'enum' || property.values.length !== 1) {
      throw new Error(
        `Cannot generate ${path}: ${discriminator} is not a single-value enum`,
      )
    }
    return { variant, value: property.values[0]?.name ?? '' }
  })

  const commonNames = new Set(
    variants[0]?.properties
      .filter((property) =>
        variants.every((variant) => {
          const candidate = variant.properties.find(
            ({ name }) => name === property.name,
          )
          if (candidate == null) return false
          // A property that varies by action attempt status is rendered per
          // status subclass, so it cannot be shared by the base class.
          if (
            (options.isActionAttempt ?? false) &&
            property.actionAttemptStatuses != null
          ) {
            return false
          }
          return (
            property.name === discriminator ||
            propertyShape(candidate) === propertyShape(property)
          )
        }),
      )
      .map(({ name }) => name) ?? [],
  )

  const commonProperties = (variants[0]?.properties ?? [])
    .filter(({ name }) => commonNames.has(name))
    .map((property) => {
      if (property.format !== 'enum') return property
      const values = uniqueEnumValues(
        variants.flatMap(
          (variant) =>
            (
              variant.properties.find(({ name }) => name === property.name) as
                EnumProperty | undefined
            )?.values ?? [],
        ),
      )
      return { ...property, values }
    })

  const discriminantProperty = commonProperties.find(
    ({ name }) => name === discriminator,
  )
  if (discriminantProperty?.format !== 'enum') {
    throw new Error(`Cannot generate ${path}: missing ${discriminator}`)
  }

  const inheritedNames = new Set(
    (options.inheritedProperties ?? []).map(({ name }) => name),
  )
  const ownCommonProperties = commonProperties.filter(
    ({ name }) => !inheritedNames.has(name),
  )

  const enumType =
    options.discriminantEnumType ??
    `\\${namespace}\\${className}\\${pascalCase(discriminator)}`
  const factory: ResourceFactory = {
    discriminant: discriminator,
    enumType,
    variants: variantInfo.map(({ value }) => ({
      enumCase: `${enumType}::${enumCaseName(value)}`,
      className: `\\${namespace}\\${className}\\${pascalCase(value)}`,
    })),
  }

  const built = buildClass(
    className,
    namespace,
    ownCommonProperties,
    path,
    depth,
    {
      ...docs,
      description: `${docs.description}${docs.description === '' ? '' : ' '}Known ${discriminator} values use subclasses; unknown values use this base class and retain their raw discriminator.`,
    },
    {
      factory,
      ...(options.extendsName == null
        ? {}
        : { extendsName: options.extendsName }),
      ...(options.inheritedProperties == null
        ? {}
        : { inheritedProperties: options.inheritedProperties }),
    },
  )
  const base = built.declaration
  if (base.kind !== 'class') throw new Error(`Cannot generate ${path}`)
  const baseName = `\\${namespace}\\${className}`
  const variantInheritedProperties = [
    ...(options.inheritedProperties ?? []),
    ...base.properties,
  ]
  for (const { variant, value } of variantInfo) {
    const variantDocs = {
      description: variant.description,
      isDeprecated: false,
      deprecationMessage: '',
    }
    const statusVariants =
      (options.isActionAttempt ?? false)
        ? expandActionAttemptByStatus(variant)
        : undefined
    if (statusVariants != null) {
      built.nestedDeclarations.push(
        buildDiscriminatedClass(
          pascalCase(value),
          `${namespace}\\${className}`,
          statusVariants,
          actionAttemptStatusName,
          `${path}.${value}`,
          depth + 1,
          variantDocs,
          {
            extendsName: baseName,
            inheritedProperties: variantInheritedProperties,
            ...(commonNames.has(actionAttemptStatusName)
              ? {
                  discriminantEnumType: `\\${namespace}\\${className}\\${pascalCase(actionAttemptStatusName)}`,
                }
              : {}),
          },
        ),
      )
      continue
    }
    const ownProperties = variant.properties.filter(
      ({ name }) => !commonNames.has(name),
    )
    built.nestedDeclarations.push(
      buildClass(
        pascalCase(value),
        `${namespace}\\${className}`,
        ownProperties,
        `${path}.${value}`,
        depth + 1,
        variantDocs,
        {
          isFinal: true,
          extendsName: baseName,
          inheritedProperties: variantInheritedProperties,
        },
      ),
    )
  }

  return built
}

const actionAttemptStatusName = 'status'

// Expand an action attempt variant into one variant per status from its
// status enum property. In each expanded variant, the status enum is narrowed
// to the single status, and any property annotated with actionAttemptStatuses
// is rendered as null for the statuses it does not list.
const expandActionAttemptByStatus = (
  variant: VariantInput,
): VariantInput[] | undefined => {
  const statusProperty = variant.properties.find(
    (property): property is EnumProperty =>
      property.name === actionAttemptStatusName && property.format === 'enum',
  )
  if (statusProperty == null) return undefined

  return statusProperty.values.map(({ name }) => {
    const status = name as ActionAttemptStatus
    return {
      description: variant.description,
      properties: variant.properties.map((property): Property => {
        if (property === statusProperty) {
          return {
            ...statusProperty,
            values: statusProperty.values.filter(
              (value) => value.name === status,
            ),
          }
        }
        const { actionAttemptStatuses } = property
        if (actionAttemptStatuses == null) return property
        if (actionAttemptStatuses.includes(status)) return property
        const nullRenderedProperty: NullRenderedProperty = {
          ...property,
          isNullable: false,
          renderAsNull: true,
        }
        return nullRenderedProperty
      }),
    }
  })
}

type NullRenderedProperty = Property & { renderAsNull: true }

const isRenderedAsNull = (property: Property): boolean =>
  (property as Partial<NullRenderedProperty>).renderAsNull === true

const buildEnum = (
  name: string,
  namespace: string,
  property: EnumProperty,
): BuiltDeclaration => ({
  declaration: {
    kind: 'enum',
    name,
    namespace,
    cases: uniqueEnumValues(property.values).map((value) => ({
      name: enumCaseName(value.name),
      value: value.name,
      description: value.description,
    })),
  },
  nestedDeclarations: [],
})

const enumCaseName = (value: string): string => {
  const name = constantCase(value)
  return /^[A-Z_]/.test(name) && !reservedClassNames.has(name.toLowerCase())
    ? name
    : `VALUE_${name}`
}

const uniqueEnumValues = <T extends { name: string }>(values: T[]): T[] => [
  ...new Map(values.map((value) => [value.name, value])).values(),
]

const propertyShape = (property: Property): string =>
  JSON.stringify(property, (key, value: unknown) =>
    [
      'description',
      'isDeprecated',
      'deprecationMessage',
      'isUndocumented',
      'undocumentedMessage',
      'isDraft',
      'draftMessage',
      'propertyGroupKey',
    ].includes(key)
      ? undefined
      : value,
  )

const propertyMetadata = (
  property: Property,
): ResourceClassPropertyMetadata => ({
  name: property.name,
  description: property.description,
  isOptional: property.isOptional,
  isNullable: property.isNullable,
  isDeprecated: property.isDeprecated,
  deprecationMessage: property.deprecationMessage,
})

const propertyDocs = (property: Property): ClassDocs => ({
  description: property.description,
  isDeprecated: property.isDeprecated,
  deprecationMessage: property.deprecationMessage,
})

const getNestedProperties = (property: Property): Property[] | undefined => {
  if (property.format === 'object') {
    return property.properties.length > 0 ? property.properties : undefined
  }
  if (property.format === 'list' && property.itemFormat === 'object') {
    return property.itemProperties.length > 0
      ? property.itemProperties
      : undefined
  }
  return undefined
}

const assertDepth = (path: string, depth: number): void => {
  if (depth > maxDepth) {
    throw new Error(
      `Cannot generate ${path}: nesting exceeded a depth of ${maxDepth}, which means the schema is cyclic`,
    )
  }
}

const assertAvailableName = (
  name: string,
  path: string,
  namespace: string,
  takenNames: Set<string>,
): void => {
  if (reservedClassNames.has(name.toLowerCase())) {
    throw new Error(`Cannot generate ${path}: ${name} is reserved in PHP`)
  }
  if (takenNames.has(name.toLowerCase())) {
    throw new Error(
      `Cannot generate ${path}: ${name} is already used in ${namespace}`,
    )
  }
  takenNames.add(name.toLowerCase())
}

const flattenDeclarations = (
  built: BuiltDeclaration,
): ResourceDeclaration[] => [
  built.declaration,
  ...built.nestedDeclarations.flatMap(flattenDeclarations),
]
