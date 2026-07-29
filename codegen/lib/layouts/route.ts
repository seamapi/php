// Builds the template context for route files (src/Routes/{Name}Client.php):
// one resource client class per file, with the use statements for everything
// its methods reference.

import {
  type PhpClient,
  type PhpClientMethod,
  sortPhpClientMethodParameters,
} from '../class-model.js'

const seamClientClass = 'Seam\\SeamClient'
const resourcesNamespace = 'Seam\\Resources'
const actionAttemptErrorClasses = [
  'Seam\\ActionAttemptFailedError',
  'Seam\\ActionAttemptTimeoutError',
]

export interface MethodLayoutContext {
  methodName: string
  description: string
  responseDescription: string
  isDeprecated: boolean
  deprecationMessage: string
  parameters: Array<{ name: string; type: string; description: string }>
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

export interface RouteLayoutContext extends ClientLayoutContext {
  useStatements: string[]
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
    description: method.description,
    responseDescription: method.responseDescription,
    isDeprecated: method.isDeprecated,
    deprecationMessage: method.deprecationMessage,
    parameters: sortedParameters.map(({ name, type, description }) => ({
      name,
      type,
      description,
    })),
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

// Child clients live in the same namespace as their parent, so only the
// SeamClient, the resource classes returned by the methods, and the action
// attempt errors thrown by poll_until_ready need importing.
const getUseStatements = (
  client: PhpClient,
  isActionAttempts: boolean,
): string[] => {
  const resourceNames = new Set(
    client.methods
      .map((m) => m.returnResource)
      .filter((resourceName) => resourceName !== ''),
  )

  if (isActionAttempts) resourceNames.add('ActionAttempt')

  return [
    seamClientClass,
    ...[...resourceNames].map((name) => `${resourcesNamespace}\\${name}`),
    ...(isActionAttempts ? actionAttemptErrorClasses : []),
  ].sort((a, b) => a.localeCompare(b))
}

export const setRouteLayoutContext = (
  client: PhpClient,
): RouteLayoutContext => {
  const isActionAttempts = client.clientName === 'ActionAttempts'

  return {
    useStatements: getUseStatements(client, isActionAttempts),
    clientName: client.clientName,
    hasChildClients: client.childClientIdentifiers.length > 0,
    childClients: client.childClientIdentifiers.map((i) => ({
      clientName: i.clientName,
      namespace: i.namespace,
    })),
    methods: client.methods.map((m) =>
      getMethodLayoutContext(m, client.clientName),
    ),
    isActionAttempts,
  }
}
