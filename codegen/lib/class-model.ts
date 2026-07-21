// Ported from @seamapi/nextlove-sdk-generator
// lib/generate-php-sdk/utils/php-client.ts. Holds the data previously carried
// by the nextlove PhpClient; all string serialization moved to the Handlebars
// layouts and their context builders.

export interface PhpClientMethodParameter {
  name: string
  type: string
  required?: boolean | undefined
  position?: number | undefined
}

export interface PhpClientMethod {
  methodName: string
  path: string
  parameters: PhpClientMethodParameter[]
  returnResource: string
  returnPath: string
  isArrayResponse: boolean
}

export interface PhpClientIdentifier {
  clientName: string
  namespace: string
}

export interface PhpClient {
  clientName: string
  namespace: string
  isParentClient: boolean
  childClientIdentifiers: PhpClientIdentifier[]
  methods: PhpClientMethod[]
}

// Verbatim port of the nextlove parameter comparator. The original expression
// `(a.position ?? a.required ? 1000 : 9999)` parses as
// `(a.position ?? a.required) ? 1000 : 9999`, so a parameter with position 0
// is falsy and lands in the 9999 tier together with the optional parameters.
// Combined with a stable sort this yields: required parameters first (in
// schema order), then everything else (in schema order).
// TODO: Fix the operator precedence so position sorts a parameter first as
// originally intended, once generated output is allowed to change. Until then,
// do not "fix" it: the generated output must stay identical.
export const sortPhpClientMethodParameters = (
  parameters: PhpClientMethodParameter[],
): PhpClientMethodParameter[] =>
  [...parameters].sort(
    (a, b) =>
      ((a.position ?? a.required) ? 1000 : 9999) -
      ((b.position ?? b.required) ? 1000 : 9999),
  )
