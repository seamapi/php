// Builds the template context for resource object files (src/Objects/{Name}.php):
// the from_json body lines and the constructor parameter lines.
//
// The blueprint does not track which resource properties are required, so
// every property is optional: from_json falls back to null for missing values
// and the constructor parameters are nullable.

import type {
  ResourceObjectProperty,
  ResourceObjectSchema,
} from '../resource-model.js'

export interface ObjectLayoutContext {
  className: string
  fromJsonProps: string[]
  constructorParams: string[]
}

const generateFromJsonProp = (property: ResourceObjectProperty): string => {
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

const generateConstructorParam = (
  property: ResourceObjectProperty,
): string => {
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

export const setObjectLayoutContext = (
  schema: ResourceObjectSchema,
): ObjectLayoutContext => {
  const sorted = [...schema.properties].sort((a, b) =>
    a.name.localeCompare(b.name),
  )

  return {
    className: schema.name,
    fromJsonProps: sorted.map(generateFromJsonProp),
    constructorParams: sorted.map(generateConstructorParam),
  }
}
