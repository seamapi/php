// Maps a blueprint JSON type to the PHP type used in generated declarations.

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
