// Data model for the generated resource client classes. All string
// serialization lives in the Handlebars layouts and their context builders.

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

// Sorts parameters with an explicit position first, then required parameters,
// then everything else, keeping the schema order within each tier.
export const sortPhpClientMethodParameters = (
  parameters: PhpClientMethodParameter[],
): PhpClientMethodParameter[] =>
  [...parameters].sort((a, b) => getParameterRank(a) - getParameterRank(b))

const getParameterRank = (parameter: PhpClientMethodParameter): number =>
  parameter.position ?? ((parameter.required ?? false) ? 1000 : 9999)
