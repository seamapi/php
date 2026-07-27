// The Metalsmith plugin that generates the PHP SDK source files.
//
// The blueprint from @seamapi/blueprint is the only input: it drives the
// resource classes written to src/Resources, and the resource client
// classes serialized into src/SeamClient.php.

import type { Blueprint, Endpoint } from '@seamapi/blueprint'
import { pascalCase } from 'change-case'
import type Metalsmith from 'metalsmith'

import type { PhpClient, PhpClientMethod } from './class-model.js'
import { setResourceLayoutContext } from './layouts/resource.js'
import { setSeamClientLayoutContext } from './layouts/seam-client.js'
import { getPhpType } from './map-php-type.js'
import { createResourceModel } from './resource-model.js'

interface Metadata {
  blueprint: Blueprint
}

const resourcesPath = 'src/Resources'
const seamClientPath = 'src/SeamClient.php'

export const routes = (
  files: Metalsmith.Files,
  metalsmith: Metalsmith,
): void => {
  const metadata = metalsmith.metadata() as Metadata
  const { blueprint } = metadata

  // Resource classes, one file per resource holding the resource class and
  // the local classes for its object properties. The resource names drive the
  // SeamClient use statements.
  const { resourceNames, resources } = createResourceModel(blueprint)

  for (const resource of resources) {
    files[`${resourcesPath}/${resource.name}.php`] = {
      contents: Buffer.from('\n'),
      layout: 'resource.hbs',
      ...setResourceLayoutContext(resource),
    }
  }

  // Resource client classes, all serialized into SeamClient.php. Each route
  // path maps to a client class, e.g. /acs/users to AcsUsersClient, wired to
  // a property on its parent client (AcsClient) or, for top-level routes, on
  // the SeamClient itself.
  const classMap = new Map<string, PhpClient>()

  const ensureClient = (namespaceSegments: string[]): PhpClient => {
    const clientName = pascalCase(namespaceSegments.join('_'))
    const existingClient = classMap.get(clientName)
    if (existingClient != null) return existingClient

    const namespace = namespaceSegments.at(-1) ?? ''
    const client: PhpClient = {
      clientName,
      namespace,
      isParentClient: namespaceSegments.length === 1,
      childClientIdentifiers: [],
      methods: [],
    }
    classMap.set(clientName, client)

    if (namespaceSegments.length > 1) {
      const parentClient = ensureClient(namespaceSegments.slice(0, -1))
      parentClient.childClientIdentifiers.push({ clientName, namespace })
    }

    return client
  }

  for (const route of blueprint.routes) {
    if (route.endpoints.length === 0) continue

    const namespaceSegments = route.path.split('/').filter((s) => s.length > 0)
    const client = ensureClient(namespaceSegments)

    for (const endpoint of route.endpoints) {
      client.methods.push(createClientMethod(endpoint))
    }
  }

  files[seamClientPath] = {
    contents: Buffer.from('\n'),
    layout: 'seam-client.hbs',
    ...setSeamClientLayoutContext([...classMap.values()], resourceNames),
  }
}

const createClientMethod = (endpoint: Endpoint): PhpClientMethod => {
  const { response } = endpoint

  const responseKey = response.responseType === 'void' ? '' : response.responseKey

  // Batch responses have no single resource type; they deserialize into the
  // Batch resource. A response whose resource type the blueprint cannot
  // resolve ('unknown') has no resource class to deserialize into, so the
  // method is generated as returning void.
  const resourceType =
    response.responseType === 'void'
      ? ''
      : response.responseType === 'resource' &&
          response.batchResourceTypes != null
        ? 'batch'
        : response.resourceType === 'unknown'
          ? ''
          : response.resourceType

  return {
    methodName: endpoint.name,
    path: endpoint.path,
    parameters: endpoint.request.parameters.map((parameter) => ({
        name: parameter.name,
        type: getPhpType(parameter),
        required: parameter.isRequired,
        // The primary identifier of a get endpoint always sorts first in the
        // method signature.
        position:
          endpoint.name === 'get' && parameter.name === `${responseKey}_id`
            ? 0
            : undefined,
      })),
    returnPath: responseKey,
    returnResource: resourceType === '' ? '' : pascalCase(resourceType),
    isArrayResponse:
      response.responseType === 'resource_list' && resourceType !== '',
  }
}
