// Builds the template context for resource files (src/Resources/{Name}.php):
// the resource class and the nested classes for its object properties, grouped
// into one braced namespace block per namespace. Each class contributes its
// from_json body lines and constructor parameter lines.
//
// The blueprint does not track which resource properties are required, so
// every property is optional: from_json falls back to null for missing values
// and the constructor parameters are nullable.

import type {
  ResourceClassProperty,
  ResourceClassSchema,
  ResourceSchema,
} from '../resource-model.js'

export interface ClassLayoutContext {
  className: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
  fromJsonProps: string[]
  constructorParams: ConstructorParamLayoutContext[]
}

export interface ConstructorParamLayoutContext {
  declaration: string
  phpDocType: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

export interface NamespaceLayoutContext {
  namespace: string
  classes: ClassLayoutContext[]
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
  }
}

const generateConstructorParam = (
  property: ResourceClassProperty,
): ConstructorParamLayoutContext => {
  let declaration: string
  let phpDocType = ''
  // Resource decoding remains tolerant of sparse response envelopes. Optional
  // properties can also be omitted when constructing a resource directly;
  // nullable properties retain the same null-safe representation.
  const defaultValue = property.isOptional ? ' = null' : ''

  switch (property.kind) {
    case 'objectReference':
      declaration = `public ${property.referenceName}|null $${property.name}${defaultValue},`
      break

    case 'listReference':
      declaration = `public array${property.isOptional ? '|null' : ''} $${property.name}${defaultValue},`
      break

    case 'record':
      phpDocType = `${property.phpDocType}|null`
      declaration = `public ${property.phpType}|null $${property.name}${defaultValue},`
      break

    case 'value': {
      const { phpType } = property
      const nullSuffix = phpType === 'mixed' ? '' : '|null'
      declaration = `public ${phpType}${nullSuffix} $${property.name}${defaultValue},`
      break
    }
  }

  return {
    declaration,
    phpDocType,
    description: property.description,
    isDeprecated: property.isDeprecated,
    deprecationMessage: property.deprecationMessage,
  }
}

const getClassLayoutContext = (
  schema: ResourceClassSchema,
): ClassLayoutContext => {
  const sorted = [...schema.properties].sort((a, b) =>
    a.name.localeCompare(b.name),
  )

  return {
    className: schema.name,
    description: schema.description,
    isDeprecated: schema.isDeprecated,
    deprecationMessage: schema.deprecationMessage,
    fromJsonProps: sorted.map(generateFromJsonProp),
    constructorParams: sorted.map(generateConstructorParam),
  }
}

export const setResourceLayoutContext = (
  resource: ResourceSchema,
): ResourceLayoutContext => {
  // First appearance order, so the resource class leads the file and every
  // owning namespace precedes the namespaces nested inside it.
  const namespaces = new Map<string, ClassLayoutContext[]>()

  for (const schema of resource.classes) {
    const classes = namespaces.get(schema.namespace)
    const context = getClassLayoutContext(schema)

    if (classes == null) {
      namespaces.set(schema.namespace, [context])
      continue
    }

    classes.push(context)
  }

  return {
    namespaces: [...namespaces.entries()].map(([namespace, classes]) => ({
      namespace,
      classes,
    })),
  }
}
