// Builds the template context for route files (src/Routes/{Name}Client.php):
// one resource client class per file, with the use statements for everything
// its methods reference.

import {
  type PhpClient,
  type PhpClientMethod,
  sortPhpClientMethodParameters,
} from '../class-model.js'

const clientInterfaceClass = 'GuzzleHttp\\ClientInterface'
const bodyClass = 'Seam\\Http\\Body'
const resolveActionAttemptClass = 'Seam\\Http\\ResolveActionAttempt'
const resourcesNamespace = 'Seam\\Resources'

export interface MethodLayoutContext {
  methodName: string
  httpMethod: string
  usesQueryParams: boolean
  description: string
  responseDescription: string
  isDeprecated: boolean
  deprecationMessage: string
  parameters: Array<{
    name: string
    type: string
    description: string
    required: boolean
    isOptional: boolean
    isNullable: boolean
  }>
  documentedParameters: Array<{
    name: string
    type: string
    description: string
  }>
  path: string
  returnType: string
  hasParams: boolean
  signatureParams: string
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
}

export interface RouteLayoutContext extends ClientLayoutContext {
  useStatements: string[]
}

const waitForActionAttemptParameter = {
  name: 'wait_for_action_attempt',
  type: 'bool|array|null',
  description:
    'Whether to wait for the action attempt to finish, optionally with timeout and polling_interval in seconds. Defaults to the value set on the client.',
  required: false,
}

const onResponseParameter = {
  name: 'on_response',
  type: 'callable|null',
  description:
    'Called with the raw response envelope, used by the paginator to read the pagination metadata.',
  required: false,
}

const getMethodLayoutContext = (
  method: PhpClientMethod,
): MethodLayoutContext => {
  const { methodName, path, parameters, returnResource, returnPath } = method

  // A method returning a list of action attempts is an ordinary list
  // endpoint: only a single returned attempt is resolved.
  const usesActionAttempt =
    returnResource === 'ActionAttempt' && !method.isArrayResponse
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
        `${(p.isNullable || p.isOptional) && p.type !== 'mixed' ? '?' : ''}${p.type} $${p.name}${p.isOptional ? ' = null' : ''}`,
    )
    .concat(
      usesActionAttempt
        ? ['bool|array|null $wait_for_action_attempt = null']
        : [],
    )
    .concat(usesOnResponse ? ['?callable $on_response = null'] : [])
    .join(', ')

  const documentedEndpointParameters = sortedParameters.map(
    ({ name, type, description, isOptional, isNullable }) => ({
      name,
      type,
      description,
      required: !isOptional,
      isOptional,
      isNullable,
    }),
  )

  return {
    methodName,
    httpMethod: method.httpMethod,
    usesQueryParams: ['GET', 'DELETE'].includes(method.httpMethod),
    description: method.description,
    responseDescription: method.responseDescription,
    isDeprecated: method.isDeprecated,
    deprecationMessage: method.deprecationMessage,
    // The request payload is built from the endpoint parameters alone.
    parameters: documentedEndpointParameters,
    // The SDK level parameters are documented alongside them so editors
    // surface all of them, but they never reach the payload.
    documentedParameters: [
      ...documentedEndpointParameters,
      ...(usesActionAttempt ? [waitForActionAttemptParameter] : []),
      ...(usesOnResponse ? [onResponseParameter] : []),
    ],
    path,
    returnType,
    hasParams: parameters.length > 0,
    signatureParams,
    usesActionAttempt,
    usesOnResponse,
    returnsVoid,
    isArrayResponse: method.isArrayResponse,
    returnResource,
    returnPath,
  }
}

// Child clients live in the same namespace as their parent, so only the HTTP
// client, the action attempt resolver, and the resource classes returned by
// the methods need importing.
const getUseStatements = (client: PhpClient): string[] => {
  const resourceNames = new Set(
    client.methods
      .map((m) => m.returnResource)
      .filter((resourceName) => resourceName !== ''),
  )

  const usesActionAttempt = client.methods.some(
    (m) => m.returnResource === 'ActionAttempt' && !m.isArrayResponse,
  )

  // Void endpoints never read the response, so they do not decode it.
  const readsBody = client.methods.some((m) => m.returnResource !== '')

  return [
    clientInterfaceClass,
    ...(readsBody ? [bodyClass] : []),
    ...(usesActionAttempt ? [resolveActionAttemptClass] : []),
    ...[...resourceNames].map((name) => `${resourcesNamespace}\\${name}`),
  ].sort((a, b) => a.localeCompare(b))
}

export const setRouteLayoutContext = (
  client: PhpClient,
): RouteLayoutContext => ({
  useStatements: getUseStatements(client),
  clientName: client.clientName,
  hasChildClients: client.childClientIdentifiers.length > 0,
  childClients: client.childClientIdentifiers.map((i) => ({
    clientName: i.clientName,
    namespace: i.namespace,
  })),
  methods: client.methods.map(getMethodLayoutContext),
})
