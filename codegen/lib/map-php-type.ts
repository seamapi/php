// Maps a blueprint parameter or property to the PHP type used in generated
// declarations.

import type { Parameter, Property } from '@seamapi/blueprint'

type RecordValueType = NonNullable<
  Extract<Parameter, { format: 'record' }>['valueTypes']
>[number]

export const getPhpType = (
  schema: Parameter | Property,
  enumType = 'string',
): string => {
  if (schema.format === 'enum') return enumType
  if (schema.format === 'record' && !('resourceType' in schema)) {
    return 'array|\\stdClass'
  }
  if (schema.format === 'number' && schema.isInt) return 'int'

  switch (schema.jsonType) {
    case 'string':
      return 'string'

    case 'number':
      return 'float'

    case 'boolean': {
      const values = [...new Set(schema.values)]
      return values.length === 1 ? String(values[0]) : 'bool'
    }

    case 'array':
      return 'array'

    default:
      return 'mixed'
  }
}

export const getPhpDocType = (schema: Parameter | Property): string => {
  if (schema.format === 'list') {
    return `list<${getListItemPhpType(schema)}>`
  }

  if (schema.format !== 'record' || 'resourceType' in schema) {
    return getPhpType(schema)
  }

  const types =
    ('valueTypes' in schema ? schema.valueTypes : undefined)?.map(
      getRecordValuePhpType,
    ) ?? []
  return `array<string, ${types.length === 0 ? 'mixed' : types.join('|')}>|\\stdClass`
}

const getListItemPhpType = (
  schema: Extract<Parameter | Property, { format: 'list' }>,
): string => {
  switch (schema.itemFormat) {
    case 'number':
      return 'isItemInt' in schema && schema.isItemInt ? 'int' : 'float'
    case 'boolean':
      return 'bool'
    case 'object':
    case 'record':
    case 'discriminated_object':
      return 'array<string, mixed>|\\stdClass'
    default:
      return 'string'
  }
}

const getRecordValuePhpType = (type: RecordValueType): string => {
  switch (type) {
    case 'string':
      return 'string'
    case 'number':
      return 'float'
    case 'integer':
      return 'int'
    case 'boolean':
      return 'bool'
    case 'object':
      return 'array<string, mixed>|\\stdClass'
    case 'array':
      return 'list<mixed>'
    default:
      throw new Error(`Unsupported JSON Schema type: ${type}`)
  }
}
