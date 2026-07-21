// Builds the template context for src/SeamClient.php.
// Mirrors the nextlove generate-seam-client.ts plus PhpClient#serialize: the
// SeamClient class with its parent client properties, and every resource
// client class with its methods.

import {
  type PhpClient,
  type PhpClientMethod,
  sortPhpClientMethodParameters,
} from '../class-model.js'

export interface MethodLayoutContext {
  methodName: string
  path: string
  returnType: string
  hasParams: boolean
  signatureParams: string
  paramNames: string[]
  usesActionAttempt: boolean
  usesOnResponse: boolean
  returnsVoid: boolean
  isArrayResponse: boolean
  returnResource: string
  returnPath: string
}

export interface ClientLayoutContext {
  clientName: string
  hasChildClients: boolean
  childClients: Array<{ clientName: string; namespace: string }>
  methods: MethodLayoutContext[]
  isActionAttempts: boolean
}

export interface SeamClientLayoutContext {
  useStatements: string[]
  parentClients: Array<{ clientName: string; namespace: string }>
  clients: ClientLayoutContext[]
}

const getMethodLayoutContext = (
  method: PhpClientMethod,
  clientName: string,
): MethodLayoutContext => {
  const { methodName, path, parameters, returnResource, returnPath } = method

  const usesActionAttempt =
    returnResource === 'ActionAttempt' && clientName !== 'ActionAttempts'
  const usesOnResponse =
    parameters.some((p) => p.name === 'page_cursor') && methodName === 'list'
  const returnsVoid = returnResource === ''
  const returnType = method.isArrayResponse
    ? 'array'
    : returnResource !== ''
      ? returnResource
      : 'void'

  const sortedParameters = sortPhpClientMethodParameters(parameters)

  const signatureParams = sortedParameters
    .map(
      (p) =>
        `${!(p.required ?? false) && p.type !== 'mixed' ? '?' : ''}${p.type} $${p.name}${(p.required ?? false) ? '' : ' = null'}`,
    )
    .concat(usesActionAttempt ? ['bool $wait_for_action_attempt = true'] : [])
    .concat(usesOnResponse ? ['?callable $on_response = null'] : [])
    .join(', ')

  return {
    methodName,
    path,
    returnType,
    hasParams: parameters.length > 0,
    signatureParams,
    paramNames: sortedParameters.map((p) => p.name),
    usesActionAttempt,
    usesOnResponse,
    returnsVoid,
    isArrayResponse: method.isArrayResponse,
    returnResource,
    returnPath,
  }
}

export const setSeamClientLayoutContext = (
  clients: PhpClient[],
  baseResourceObjectNames: string[],
): SeamClientLayoutContext => ({
  useStatements: baseResourceObjectNames,
  parentClients: clients
    .filter((c) => c.isParentClient)
    .map((c) => ({ clientName: c.clientName, namespace: c.namespace })),
  clients: clients.map((c) => ({
    clientName: c.clientName,
    hasChildClients: c.childClientIdentifiers.length > 0,
    childClients: c.childClientIdentifiers.map((i) => ({
      clientName: i.clientName,
      namespace: i.namespace,
    })),
    methods: c.methods.map((m) => getMethodLayoutContext(m, c.clientName)),
    isActionAttempts: c.clientName === 'ActionAttempts',
  })),
})
