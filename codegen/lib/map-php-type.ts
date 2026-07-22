// Maps a blueprint JSON type to the PHP type used in generated declarations.

import type { Parameter } from '@seamapi/blueprint'

export const getParameterPhpType = (parameter: Parameter): string =>
  parameter.format === 'number' && parameter.isInt
    ? 'int'
    : getPhpType(parameter.jsonType)

export const getPhpType = (jsonType: string): string => {
  switch (jsonType) {
    case 'string':
      return 'string'

    case 'number':
      return 'float'

    case 'boolean':
      return 'bool'

    case 'array':
      return 'array'

    default:
      return 'mixed'
  }
}
