// Builds the template context for resource object files (src/Objects/{Name}.php).
// Mirrors the nextlove generate-resource-object-class.ts serialization: the
// from_json body lines and the constructor parameter lines, each already
// sorted with the nextlove property comparator.

import { getPhpType } from '../map-php-type.js'
import type { ExtractedResourceObjectSchema ,PropertySchemaWithReferenceObject } from '../openapi/deep-extract-resource-object-schemas.js'

export interface ObjectLayoutContext {
  className: string
  fromJsonProps: string[]
  constructorParams: string[]
}

const compareProperties = (
  [propA, schemaA]: [string, PropertySchemaWithReferenceObject],
  [propB, schemaB]: [string, PropertySchemaWithReferenceObject],
  requiredPropertyNames: string[],
): number => {
  const aRequired = requiredPropertyNames.includes(propA)
  const bRequired = requiredPropertyNames.includes(propB)
  const aNullable = 'nullable' in schemaA && schemaA.nullable === true
  const bNullable = 'nullable' in schemaB && schemaB.nullable === true

  if (!aNullable !== !bNullable) return !aNullable ? -1 : 1
  if (aRequired !== bRequired) return aRequired ? -1 : 1
  return propA.localeCompare(propB)
}

const isObjectReferencingResource = (
  schema: PropertySchemaWithReferenceObject,
): boolean =>
  'type' in schema &&
  schema.type === 'object' &&
  'referenceObjectTypeName' in schema

const isArrayReferencingResource = (
  schema: PropertySchemaWithReferenceObject,
): boolean =>
  'type' in schema &&
  schema.type === 'array' &&
  'referenceObjectTypeName' in schema

const isPropertyRequired = (
  propName: string,
  schema: PropertySchemaWithReferenceObject,
  requiredPropertyNames: string[],
): boolean =>
  'nullable' in schema
    ? !(schema.nullable ?? false)
    : requiredPropertyNames.includes(propName)

const generateFromJsonProp = (
  propName: string,
  schema: PropertySchemaWithReferenceObject,
  requiredPropertyNames: string[],
): string => {
  const required = isPropertyRequired(propName, schema, requiredPropertyNames)
  const refName =
    'referenceObjectTypeName' in schema ? schema.referenceObjectTypeName : ''

  if (isObjectReferencingResource(schema)) {
    return `${propName}: ${required ? '' : `isset($json->${propName}) ? `}${refName}::from_json($json->${propName})${required ? ',' : ' : null,'}`
  }

  if (isArrayReferencingResource(schema)) {
    return `${propName}: array_map(fn ($${propName[0]}) => ${refName}::from_json($${propName[0]}), $json->${propName} ?? []),`
  }

  return `${propName}: $json->${propName}${required ? ',' : ' ?? null,'}`
}

const generateConstructorParam = (
  propName: string,
  schema: PropertySchemaWithReferenceObject,
  requiredPropertyNames: string[],
): string => {
  const phpType = isObjectReferencingResource(schema)
    ? ((schema as { referenceObjectTypeName?: string })
        .referenceObjectTypeName ?? 'mixed')
    : getPhpType('type' in schema ? schema.type : 'mixed')
  const required = isPropertyRequired(propName, schema, requiredPropertyNames)
  const nullSuffix = phpType === 'mixed' ? '' : required ? '' : ' | null'

  return `public ${phpType}${nullSuffix} $${propName},`
}

export const setObjectLayoutContext = (
  schema: ExtractedResourceObjectSchema,
): ObjectLayoutContext => {
  const { name, requiredPropertyNames } = schema
  const properties = { ...schema.properties }

  // TODO: Remove this created_at injection once seam-connect is patched and
  // generated output is allowed to change. The nextlove generator added a
  // created_at string property to every Errors and Warnings resource class.
  if (name.endsWith('Errors') || name.endsWith('Warnings')) {
    properties['created_at'] = { type: 'string' }
  }

  const sorted = Object.entries(properties).sort((a, b) =>
    compareProperties(a, b, requiredPropertyNames),
  )

  return {
    className: name,
    fromJsonProps: sorted.map(([propName, propSchema]) =>
      generateFromJsonProp(propName, propSchema, requiredPropertyNames),
    ),
    constructorParams: sorted.map(([propName, propSchema]) =>
      generateConstructorParam(propName, propSchema, requiredPropertyNames),
    ),
  }
}
