// Builds the template context for generated resource files.

import type {
  ResourceClassProperty,
  ResourceClassSchema,
  ResourceEnumSchema,
  ResourceSchema,
} from '../resource-model.js'

export interface ClassLayoutContext {
  className: string
  hasRawJson: boolean
  description: string
  isDeprecated: boolean
  deprecationMessage: string
  isFinal: boolean
  extendsName: string
  factory?: FactoryLayoutContext
  fromJsonProps: string[]
  constructorParams: ConstructorParamLayoutContext[]
  parentArgs: string[]
}

export interface FactoryLayoutContext {
  discriminant: string
  enumType: string
  variants: Array<{ enumCase: string; className: string }>
}

export interface ConstructorParamLayoutContext {
  declaration: string
  phpDocType: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

export interface EnumLayoutContext {
  enumName: string
  cases: Array<{ name: string; value: string; description: string }>
}

export interface NamespaceLayoutContext {
  namespace: string
  classes: ClassLayoutContext[]
  enums: EnumLayoutContext[]
}

export interface ResourceLayoutContext {
  namespaces: NamespaceLayoutContext[]
}

const generateFromJsonProp = (property: ResourceClassProperty): string => {
  const { name } = property

  switch (property.kind) {
    case 'objectReference':
      return `${name}: isset($json->${name}) ? ${property.referenceName}::from_json($json->${name}) : null,`

    case 'listReference':
      return `${name}: array_map(fn ($${name[0]}) => ${property.referenceName}::from_json($${name[0]}), $json->${name} ?? []),`

    case 'record':
      return `${name}: $json->${name} ?? null,`

    case 'value':
      return `${name}: $json->${name} ?? null,`
    case 'null':
      return `${name}: null,`
  }
}

const generateConstructorParam = (
  property: ResourceClassProperty,
  promote: boolean,
): ConstructorParamLayoutContext => {
  let type: string
  let phpDocType = ''
  const defaultValue = property.isOptional ? ' = null' : ''

  switch (property.kind) {
    case 'objectReference':
      type = `${property.referenceName}|null`
      break

    case 'listReference':
      type = `array${property.isOptional ? '|null' : ''}`
      phpDocType = `list<${property.referenceName}>${property.isOptional ? '|null' : ''}`
      break

    case 'record':
      type = `${property.phpType}|null`
      phpDocType = `${property.phpDocType}|null`
      break

    case 'value': {
      const nullSuffix = property.phpType === 'mixed' ? '' : '|null'
      type = `${property.phpType}${nullSuffix}`
      phpDocType =
        property.phpDocType === '' || property.phpDocType === property.phpType
          ? ''
          : `${property.phpDocType}|null`
      break
    }
    case 'null':
      type = 'null'
      break
  }

  return {
    declaration: `${promote ? 'public ' : ''}${type} $${property.name}${defaultValue},`,
    phpDocType,
    description: property.description,
    isDeprecated: property.isDeprecated,
    deprecationMessage: property.deprecationMessage,
  }
}

const getClassLayoutContext = (
  schema: ResourceClassSchema,
): ClassLayoutContext => {
  const inheritedNames = new Set(
    schema.inheritedProperties.map(({ name }) => name),
  )
  const properties = sortRequiredFirst([
    ...schema.inheritedProperties,
    ...schema.properties,
  ])

  return {
    className: schema.name,
    // raw_json exists for the webhook verify return, so the base Event carries
    // it and nothing else does. The variants inherit it.
    hasRawJson: schema.name === 'Event' && schema.extendsName === '',
    description: schema.description,
    isDeprecated: schema.isDeprecated,
    deprecationMessage: schema.deprecationMessage,
    isFinal: schema.isFinal,
    extendsName: schema.extendsName,
    ...(schema.factory == null ? {} : { factory: schema.factory }),
    fromJsonProps: properties.map(generateFromJsonProp),
    constructorParams: properties.map((property) =>
      generateConstructorParam(property, !inheritedNames.has(property.name)),
    ),
    parentArgs: schema.inheritedProperties.map(
      ({ name }) => `${name}: $${name},`,
    ),
  }
}

const getEnumLayoutContext = (
  schema: ResourceEnumSchema,
): EnumLayoutContext => ({
  enumName: schema.name,
  cases: schema.cases.map((enumCase) => ({
    ...enumCase,
    value: JSON.stringify(enumCase.value),
  })),
})

const sortRequiredFirst = (
  properties: ResourceClassProperty[],
): ResourceClassProperty[] =>
  [...properties].sort(
    (a, b) =>
      Number(a.isOptional) - Number(b.isOptional) ||
      a.name.localeCompare(b.name),
  )

export const setResourceLayoutContext = (
  resource: ResourceSchema,
): ResourceLayoutContext => {
  const namespaces = new Map<string, NamespaceLayoutContext>()

  for (const declaration of resource.declarations) {
    let context = namespaces.get(declaration.namespace)
    if (context == null) {
      context = { namespace: declaration.namespace, classes: [], enums: [] }
      namespaces.set(declaration.namespace, context)
    }

    if (declaration.kind === 'class') {
      context.classes.push(getClassLayoutContext(declaration))
    } else {
      context.enums.push(getEnumLayoutContext(declaration))
    }
  }

  return { namespaces: [...namespaces.values()] }
}
