// TEMPORARY: Verbatim port of @seamapi/nextlove-sdk-generator
// lib/generate-php-sdk/utils/get-php-type.ts. This is a frozen output-parity
// workaround: it exists only so the generated output stays byte-identical to
// the previous generator. Do not review, refactor, or improve it. Note the
// integer case is intentionally absent: it falls through to the default and
// maps to mixed, matching the previous generator (blueprint would collapse
// integer to number and produce float instead).
// TODO: Delete this file and derive types from @seamapi/blueprint parameter
// and resource properties once generated output is allowed to change.

export const getPhpType = (zodType: string | undefined): string => {
  switch (zodType) {
    case 'string':
      return 'string'

    case 'number':
      return 'float'

    case 'boolean':
      return 'bool'

    case 'object':
      return 'mixed'

    case 'array':
      return 'array'

    default:
      return 'mixed'
  }
}
