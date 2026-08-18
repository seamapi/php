// Maps a blueprint parameter or property to the PHP type used in generated
// declarations.

import type { Parameter, Property } from '@seamapi/blueprint'

export const getPhpType = (schema: Parameter | Property): string => {
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
