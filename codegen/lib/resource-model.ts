// Builds the resource class model for src/Resources from the blueprint.
//
// Each blueprint resource becomes a PHP class in its own file. A nested object
// property, or a list of objects, becomes its own class named after the
// property alone and declared in the namespace of the class that owns it, so
// the device battery is Seam\Resources\Device\Properties\Battery. Nesting the
// classes keeps Seam\Resources free of the hundreds of names that exist only
// to type a property, and lets two properties of the same name at different
// depths keep their own shapes.
//
// Discriminated unions (events, action attempts, and discriminated object
// lists) are flattened into a single class holding the union of the variant
// properties.

import type { Blueprint, Property, Resource } from '@seamapi/blueprint'
import { pascalCase } from 'change-case'

import { getPhpDocType, getPhpType } from './map-php-type.js'
import { mergeProperties } from './merge-properties.js'

export type ResourceClassProperty =
  | ({ kind: 'value'; phpType: string } & ResourceClassPropertyMetadata)
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

export interface ResourceClassSchema {
  name: string
  namespace: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
  properties: ResourceClassProperty[]
}

export interface ResourceSchema {
  name: string
  classes: ResourceClassSchema[]
}

export interface ResourceModel {
  resourceNames: string[]
  resources: ResourceSchema[]
}

const rootNamespace = 'Seam\\Resources'

// A cyclic schema would recurse forever. Real shapes are nowhere near this
// deep, so exceeding it means the blueprint is cyclic rather than nested.
const maxDepth = 16

// PHP reserves these as type names or keywords, so a class cannot be called
// any of them. Class names are case insensitive, so the check has to be too.
const reservedClassNames = new Set([
  // Type names.
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
  // Keywords.
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

interface BuiltClass {
  schema: ResourceClassSchema
  nestedClasses: BuiltClass[]
}

export const createResourceModel = (blueprint: Blueprint): ResourceModel => {
  const baseResources = new Map<string, Property[]>()

  for (const resource of blueprint.resources) {
    baseResources.set(resource.resourceType, resource.properties)
  }

  // The blueprint models events and action attempts as one resource per
  // variant. The PHP SDK has a single class for each, so the variants are
  // merged into one schema.
  const { events } = blueprint
  if (events.length > 0) {
    baseResources.set(
      'event',
      mergeProperties(
        events.map((event) => event.properties),
        'event',
      ),
    )
  }

  const { actionAttempts } = blueprint
  if (actionAttempts.length > 0) {
    baseResources.set(
      'action_attempt',
      mergeProperties(
        actionAttempts.map((actionAttempt) => actionAttempt.properties),
        'action_attempt',
      ),
    )
  }

  const baseResourceTypes = [...baseResources.keys()].sort()

  const resources = baseResourceTypes.map((resourceType) => {
    const name = pascalCase(resourceType)
    const sourceResource = getSourceResource(blueprint, resourceType)

    const built = buildClass(
      name,
      rootNamespace,
      baseResources.get(resourceType) ?? [],
      resourceType,
      0,
      {
        description: sourceResource?.description ?? '',
        isDeprecated: sourceResource?.isDeprecated ?? false,
        deprecationMessage: sourceResource?.deprecationMessage ?? '',
      },
    )

    return { name, classes: flattenClasses(built) }
  })

  return {
    resourceNames: resources.map((resource) => resource.name),
    resources,
  }
}

const getSourceResource = (
  blueprint: Blueprint,
  resourceType: string,
): Resource | undefined =>
  blueprint.resources.find(
    (resource) => resource.resourceType === resourceType,
  ) ??
  (resourceType === 'event'
    ? blueprint.events[0]
    : resourceType === 'action_attempt'
      ? blueprint.actionAttempts[0]
      : undefined)

const buildClass = (
  className: string,
  namespace: string,
  classProperties: Property[],
  path: string,
  depth: number,
  docs: ClassDocs,
): BuiltClass => {
  if (depth > maxDepth) {
    throw new Error(
      `Cannot generate ${path}: nesting exceeded a depth of ${maxDepth}, which means the schema is cyclic`,
    )
  }

  // A class at namespace N with short name S owns its children at N\S.
  const nestedNamespace = `${namespace}\\${className}`
  const nestedClasses: BuiltClass[] = []
  const takenClassNames = new Set<string>()

  const properties = classProperties.map((property): ResourceClassProperty => {
    const metadata = {
      name: property.name,
      description: property.description,
      isOptional: property.isOptional,
      isNullable: property.isNullable,
      isDeprecated: property.isDeprecated,
      deprecationMessage: property.deprecationMessage,
    }

    const nestedProperties = getNestedProperties(
      property,
      `${path}.${property.name}`,
    )

    if (nestedProperties == null) {
      return property.format === 'record' && !('resourceType' in property)
        ? {
            ...metadata,
            kind: 'record',
            phpType: getPhpType(property),
            phpDocType: getPhpDocType(property),
          }
        : { ...metadata, kind: 'value', phpType: getPhpType(property) }
    }

    const nestedClassName = pascalCase(property.name)
    const nestedPath = `${path}.${property.name}`

    if (reservedClassNames.has(nestedClassName.toLowerCase())) {
      throw new Error(
        `Cannot generate ${nestedPath}: ${nestedClassName} is reserved in PHP`,
      )
    }

    if (takenClassNames.has(nestedClassName)) {
      throw new Error(
        `Cannot generate ${nestedPath}: ${nestedClassName} is already used by a sibling property in ${nestedNamespace}`,
      )
    }

    takenClassNames.add(nestedClassName)

    nestedClasses.push(
      buildClass(
        nestedClassName,
        nestedNamespace,
        nestedProperties,
        nestedPath,
        depth + 1,
        {
          description: property.description,
          isDeprecated: property.isDeprecated,
          deprecationMessage: property.deprecationMessage,
        },
      ),
    )

    // Referenced relative to the namespace the owning class is declared in,
    // so PHP resolves Device\Properties from within Seam\Resources.
    return {
      ...metadata,
      kind: property.format === 'list' ? 'listReference' : 'objectReference',
      referenceName: `${className}\\${nestedClassName}`,
    }
  })

  return {
    schema: {
      name: className,
      namespace,
      ...docs,
      properties,
    },
    nestedClasses,
  }
}

// Decides whether a property earns a class of its own. An object with no
// documented properties stays a plain value rather than becoming an empty
// class.
const getNestedProperties = (
  property: Property,
  path: string,
): Property[] | undefined => {
  if (property.format === 'object') {
    return property.properties.length > 0 ? property.properties : undefined
  }

  if (property.format === 'list') {
    if (property.itemFormat === 'object') {
      return property.itemProperties.length > 0
        ? property.itemProperties
        : undefined
    }

    if (property.itemFormat === 'discriminated_object') {
      const itemProperties = mergeProperties(
        property.variants.map((variant) => variant.properties),
        path,
      )
      return itemProperties.length > 0 ? itemProperties : undefined
    }
  }

  return undefined
}

// Depth first, so an owning class always precedes the children it references.
const flattenClasses = (built: BuiltClass): ResourceClassSchema[] => [
  built.schema,
  ...built.nestedClasses.flatMap(flattenClasses),
]
