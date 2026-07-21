// TEMPORARY: Verbatim port of @seamapi/nextlove-sdk-generator
// lib/generate-php-sdk/utils/deep-extract-resource-object-schemas.ts. This is
// a frozen output-parity workaround: it exists only so the generated output
// stays byte-identical to the previous generator. Do not review, refactor, or
// improve it. Local identifiers were camelCased to satisfy lint, but the
// logic, including which nested object and array schemas are split into their
// own resource classes, is unchanged.
// TODO: Delete this file and use blueprint.resources (plus its nested
// property schemas) once generated output is allowed to change.

import { pascalCase } from 'change-case'

import { deepFlattenOneOfAndAllOfSchema } from './deep-flatten-one-of-and-all-of-schema.js'
import type { PropertySchema } from './types.js'

export type PropertySchemaWithReferenceObject =
  | PropertySchema
  | {
      type: 'object' | 'array'
      referenceObjectTypeName?: string
      nullable?: boolean
    }

export interface ExtractedResourceObjectSchema {
  name: string
  properties: Record<string, PropertySchemaWithReferenceObject>
  requiredPropertyNames: string[]
}

interface DeepExtractParams {
  schemaName: string
  schemaBody: PropertySchema
}

export function deepExtractResourceObjectSchemas({
  schemaName,
  schemaBody,
}: DeepExtractParams): Record<string, ExtractedResourceObjectSchema> {
  const extractedResourceObjectSchemas: Record<
    string,
    ExtractedResourceObjectSchema
  > = {}

  const createResourceSchema = ({
    name,
    properties,
    requiredPropertyNames = [],
  }: {
    name: string
    properties: Record<string, PropertySchema>
    requiredPropertyNames?: string[]
  }): void => {
    if (extractedResourceObjectSchemas[name] == null) {
      extractedResourceObjectSchemas[name] = {
        name: pascalCase(name),
        properties: processPropertySchemas(properties),
        requiredPropertyNames,
      }
    }
  }

  const processPropertySchemas = (
    propertySchemas: Record<string, PropertySchema>,
  ): Record<string, PropertySchemaWithReferenceObject> => {
    const propertySchemasWithTypeReferences: Record<
      string,
      PropertySchemaWithReferenceObject
    > = {}

    for (const propertyName in propertySchemas) {
      const propertySchema = propertySchemas[propertyName] as any
      const referenceObjectTypeName = pascalCase(
        `${schemaName}_${propertyName}`,
      )
      if ('$ref' in propertySchema) {
        // eslint-disable-next-line no-console
        console.error('$ref not currently supported when extracting schemas')
        continue
      }

      const isAllOfOrOneOfSchema =
        'allOf' in propertySchema || 'oneOf' in propertySchema
      const isObjectSchema =
        !isAllOfOrOneOfSchema &&
        propertySchema.type === 'object' &&
        'properties' in propertySchema
      const isArrayNonPrimitiveSchema =
        !isAllOfOrOneOfSchema &&
        propertySchema.type === 'array' &&
        'items' in propertySchema &&
        ('properties' in propertySchema.items ||
          'allOf' in propertySchema.items ||
          'oneOf' in propertySchema.items)
      const isNullableSet = 'nullable' in propertySchema

      if (isObjectSchema) {
        propertySchemasWithTypeReferences[propertyName] = {
          type: 'object',
          referenceObjectTypeName,
          ...(isNullableSet && { nullable: propertySchema.nullable }),
        }

        createResourceSchema({
          name: referenceObjectTypeName,
          properties: propertySchema.properties,
          requiredPropertyNames: propertySchema.required,
        })
      } else if (isArrayNonPrimitiveSchema) {
        propertySchemasWithTypeReferences[propertyName] = {
          type: 'array',
          referenceObjectTypeName,
          ...(isNullableSet && { nullable: propertySchema.nullable }),
        }

        let schemaProperties = propertySchema.items?.properties
        let requiredSchemaPropertyNames = propertySchema.items?.required

        if (
          'allOf' in propertySchema.items ||
          'oneOf' in propertySchema.items
        ) {
          const flattenedSchema = deepFlattenOneOfAndAllOfSchema(
            propertySchema.items,
          ) as any

          schemaProperties =
            'properties' in flattenedSchema
              ? flattenedSchema.properties
              : flattenedSchema
          requiredSchemaPropertyNames =
            'required' in flattenedSchema ? flattenedSchema.required : []
        }

        createResourceSchema({
          name: referenceObjectTypeName,
          properties: schemaProperties,
          requiredPropertyNames: requiredSchemaPropertyNames,
        })
      } else if (isAllOfOrOneOfSchema) {
        const flattenedSchema = deepFlattenOneOfAndAllOfSchema(
          propertySchema,
        ) as any

        if ('properties' in flattenedSchema) {
          propertySchemasWithTypeReferences[propertyName] = {
            type: flattenedSchema.type,
            referenceObjectTypeName,
          }

          createResourceSchema({
            name: referenceObjectTypeName,
            properties: flattenedSchema.properties,
            requiredPropertyNames: flattenedSchema.required,
          })
        } else {
          propertySchemasWithTypeReferences[propertyName] = flattenedSchema
        }
        continue
      } else {
        propertySchemasWithTypeReferences[propertyName] = {
          type: propertySchema.type,
          ...(isNullableSet && { nullable: propertySchema.nullable }),
        }
      }
    }

    return propertySchemasWithTypeReferences
  }

  const body = schemaBody as any
  let baseResourceProperties: Record<string, PropertySchema> | undefined

  if ('allOf' in body || 'oneOf' in body) {
    const flattenedBaseResourceSchema = deepFlattenOneOfAndAllOfSchema(
      body,
    ) as any

    baseResourceProperties =
      'properties' in flattenedBaseResourceSchema
        ? flattenedBaseResourceSchema.properties
        : {}
  }

  baseResourceProperties ??= 'properties' in body ? body.properties : {}

  createResourceSchema({
    name: pascalCase(schemaName),
    properties: baseResourceProperties ?? {},
    requiredPropertyNames: 'required' in body ? body.required : [],
  })

  return extractedResourceObjectSchemas
}
