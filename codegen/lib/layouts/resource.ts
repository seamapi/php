// Builds the template context for resource files (src/Resources/{Name}.php):
// the resource class followed by the local classes for its object properties.
// Each class contributes its from_json body lines and constructor parameter
// lines.
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
  fromJsonProps: string[]
  constructorParams: string[]
}

export interface ResourceLayoutContext {
  classes: ClassLayoutContext[]
}

const generateFromJsonProp = (property: ResourceClassProperty): string => {
  const { name } = property

  switch (property.kind) {
    case 'objectReference':
      return `${name}: isset($json->${name}) ? ${property.referenceName}::from_json($json->${name}) : null,`

    case 'listReference':
      return `${name}: array_map(fn ($${name[0]}) => ${property.referenceName}::from_json($${name[0]}), $json->${name} ?? []),`

    case 'value':
      return `${name}: $json->${name} ?? null,`
  }
}

const generateConstructorParam = (property: ResourceClassProperty): string => {
  switch (property.kind) {
    case 'objectReference':
      return `public ${property.referenceName}|null $${property.name},`

    case 'listReference':
      return `public array $${property.name},`

    case 'value': {
      const { phpType } = property
      const nullSuffix = phpType === 'mixed' ? '' : '|null'
      return `public ${phpType}${nullSuffix} $${property.name},`
    }
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
    fromJsonProps: sorted.map(generateFromJsonProp),
    constructorParams: sorted.map(generateConstructorParam),
  }
}

export const setResourceLayoutContext = (
  resource: ResourceSchema,
): ResourceLayoutContext => ({
  classes: [resource.resourceClass, ...resource.localClasses].map(
    getClassLayoutContext,
  ),
})
