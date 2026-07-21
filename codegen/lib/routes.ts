// The Metalsmith plugin that generates the PHP SDK route files.
// Ported from @seamapi/nextlove-sdk-generator
// lib/generate-php-sdk/generate-php-sdk.ts, restructured to mirror the
// javascript-http codegen plugin (lib/connect.ts).
//
// The blueprint from @seamapi/blueprint drives the iteration order and the
// route, endpoint, and namespace structure. The raw OpenAPI spec is still
// consulted wherever the previous nextlove generator derived output from data
// the blueprint normalizes differently; each of those spots is marked with a
// TODO so they can migrate to the blueprint once output is allowed to change,
// and the supporting code lives in files marked TEMPORARY that will be deleted
// with them.

import type { Blueprint } from '@seamapi/blueprint'
import * as types from '@seamapi/types/connect'
import { pascalCase } from 'change-case'
import type Metalsmith from 'metalsmith'

import type { PhpClient, PhpClientMethodParameter } from './class-model.js'
import { setObjectLayoutContext } from './layouts/object.js'
import { setSeamClientLayoutContext } from './layouts/seam-client.js'
import { getPhpType } from './map-php-type.js'
import { deepExtractResourceObjectSchemas } from './openapi/deep-extract-resource-object-schemas.js'
import { getFilteredRoutes } from './openapi/get-filtered-routes.js'
import { getParameterAndResponseSchema } from './openapi/get-parameter-and-response-schema.js'
import { mapParentToChildResources } from './openapi/map-parent-to-children-resource.js'
import type { OpenapiSchema } from './openapi/types.js'

interface Metadata {
  blueprint: Blueprint
}

const objectsPath = 'src/Objects'
const seamClientPath = 'src/SeamClient.php'

const openapi = types.openapi as unknown as OpenapiSchema

export const routes = (
  files: Metalsmith.Files,
  metalsmith: Metalsmith,
): void => {
  const metadata = metalsmith.metadata() as Metadata
  const { blueprint } = metadata

  // TODO: Derive the parent to child resource map from blueprint.namespaces
  // once generated output is allowed to change.
  const rawRoutes = getFilteredRoutes(openapi)
  const parentToChildResourcesMap = mapParentToChildResources(rawRoutes)

  // Resource object classes, one file per (deeply extracted) schema. The order
  // of base resource names drives the SeamClient use statements.
  const baseResourceObjectNames: string[] = []
  for (const [schemaName, schema] of Object.entries(
    openapi.components.schemas,
  )) {
    baseResourceObjectNames.push(pascalCase(schemaName))

    const extracted = deepExtractResourceObjectSchemas({
      schemaName,
      schemaBody: schema,
    })

    for (const extractedSchema of Object.values(extracted)) {
      // TODO: Remove this ActionAttempt result widening once the nextlove
      // OpenAPI generator is fixed and generated output is allowed to change.
      const schemaForContext =
        extractedSchema.name === 'ActionAttempt'
          ? {
              ...extractedSchema,
              properties: {
                ...extractedSchema.properties,
                result: { type: 'object' as const, nullable: true },
              },
            }
          : extractedSchema

      files[`${objectsPath}/${extractedSchema.name}.php`] = {
        contents: Buffer.from('\n'),
        layout: 'object.hbs',
        ...setObjectLayoutContext(schemaForContext),
      }
    }
  }

  // Resource client classes, all serialized into SeamClient.php.
  const classMap = new Map<string, PhpClient>()

  const processClient = (resourceName: string): void => {
    const childClientIdentifiers = (
      parentToChildResourcesMap[resourceName] ?? []
    ).map((childResource) => ({
      clientName: pascalCase(`${resourceName} ${childResource}`),
      namespace: childResource,
    }))
    const isParentClient = Object.keys(parentToChildResourcesMap).includes(
      resourceName,
    )
    const pascalResourceName = pascalCase(resourceName)

    classMap.set(pascalResourceName, {
      clientName: pascalResourceName,
      namespace: resourceName,
      isParentClient,
      childClientIdentifiers,
      methods: [],
    })
  }

  for (const route of blueprint.routes) {
    for (const endpoint of route.endpoints) {
      const post = openapi.paths[endpoint.path]?.post
      if (post == null) continue

      // TODO: Filter on endpoint.isUndocumented and route.isUndocumented from
      // the blueprint once generated output is allowed to change. The raw
      // OpenAPI extensions are used here to include exactly the same route set
      // as the previous nextlove getFilteredRoutes plus its group-name guard.
      if (post['x-undocumented'] != null) continue
      if ((post.summary ?? '').startsWith('/seam/')) continue
      if (post['x-fern-sdk-group-name'] == null) continue

      const groupNames = [...post['x-fern-sdk-group-name']]
      const [baseResource] = groupNames
      const namespace = groupNames.join('_')
      const clientName = pascalCase(namespace)

      if (!classMap.has(clientName)) {
        processClient(namespace)
      }

      /*
        Special case when we don't have routes for a base resource
        and thus a respective x-fern-sdk-group-name for ex. /noise_sensors
      */
      if (baseResource != null && !classMap.has(pascalCase(baseResource))) {
        processClient(baseResource)
      }

      const client = classMap.get(clientName)

      if (client == null) {
        // eslint-disable-next-line no-console
        console.warn(`No client for "${clientName}", skipping`)
        continue
      }

      const { parameterSchema, responseObjType, responseArrType } =
        getParameterAndResponseSchema({ path: endpoint.path, post })

      if (parameterSchema == null) {
        // eslint-disable-next-line no-console
        console.warn(`No parameter schema for "${endpoint.path}", skipping`)
        continue
      }

      const returnResource = responseObjType ?? responseArrType ?? ''

      client.methods.push({
        methodName: post['x-fern-sdk-method-name'] ?? '',
        path: endpoint.path,
        // TODO: Use endpoint.request.parameters from the blueprint once
        // generated output is allowed to change. The blueprint collapses
        // integer to number and flattens unions differently, so parameters are
        // derived from the raw OpenAPI schema for identical output.
        parameters: Object.entries(parameterSchema.properties)
          .filter(
            ([, paramVal]) =>
              'type' in paramVal ||
              ('oneOf' in paramVal &&
                'type' in ((paramVal as any).oneOf[0] ?? {})),
          )
          .map(([paramName, paramVal]): PhpClientMethodParameter => {
            const raw = paramVal as any
            return {
              name: paramName,
              type: getPhpType(raw?.type ?? raw.oneOf[0].type),
              required: parameterSchema.required?.includes(paramName),
              position:
                post['x-fern-sdk-method-name'] === 'get' &&
                paramName === `${post['x-fern-sdk-return-value']}_id`
                  ? 0
                  : undefined,
            }
          }),
        returnPath: post['x-fern-sdk-return-value'] ?? '',
        returnResource: pascalCase(returnResource),
        isArrayResponse: Boolean(responseArrType),
      })
    }
  }

  files[seamClientPath] = {
    contents: Buffer.from('\n'),
    layout: 'seam-client.hbs',
    ...setSeamClientLayoutContext(
      [...classMap.values()],
      baseResourceObjectNames,
    ),
  }
}
