// Builds the resource object class model for src/Objects from the blueprint.
//
// Each blueprint resource becomes a PHP class. Nested object properties and
// lists of objects are split into their own classes named after the base
// resource and the property, e.g. the device battery property becomes
// DeviceBattery. Discriminated unions (events, action attempts, and
// discriminated object lists) are flattened into a single class with the
// union of the variant properties.

import type { Blueprint, Property } from '@seamapi/blueprint'
import { pascalCase } from 'change-case'

import { getPhpType } from './map-php-type.js'

export type ResourceObjectProperty =
  | { name: string; kind: 'value'; phpType: string }
  | { name: string; kind: 'objectReference'; referenceName: string }
  | { name: string; kind: 'listReference'; referenceName: string }

export interface ResourceObjectSchema {
  name: string
  properties: ResourceObjectProperty[]
}

export interface ResourceObjectModel {
  baseResourceNames: string[]
  schemas: ResourceObjectSchema[]
}

export const createResourceObjectModel = (
  blueprint: Blueprint,
): ResourceObjectModel => {
  const baseResources = new Map<string, Property[]>()

  for (const resource of blueprint.resources) {
    if (resource.isUndocumented) continue
    baseResources.set(
      resource.resourceType,
      documentedProperties(resource.properties),
    )
  }

  // The blueprint models events and action attempts as one resource per
  // variant. The PHP SDK has a single class for each, so the variants are
  // merged into one schema.
  const events = blueprint.events.filter((event) => !event.isUndocumented)
  if (events.length > 0) {
    baseResources.set(
      'event',
      mergeProperties(
        events.map((event) => documentedProperties(event.properties)),
      ),
    )
  }

  const actionAttempts = blueprint.actionAttempts.filter(
    (actionAttempt) => !actionAttempt.isUndocumented,
  )
  if (actionAttempts.length > 0) {
    baseResources.set(
      'action_attempt',
      mergeProperties(
        actionAttempts.map((actionAttempt) =>
          documentedProperties(actionAttempt.properties),
        ),
      ),
    )
  }

  const schemas = new Map<string, ResourceObjectSchema>()

  const addSchema = (
    name: string,
    properties: Property[],
    baseName: string,
  ): void => {
    if (schemas.has(name)) return
    const schema: ResourceObjectSchema = { name, properties: [] }
    schemas.set(name, schema)
    schema.properties = properties.map((property) =>
      createResourceObjectProperty(property, baseName, addSchema),
    )
  }

  const baseResourceTypes = [...baseResources.keys()].sort()
  for (const resourceType of baseResourceTypes) {
    addSchema(
      pascalCase(resourceType),
      baseResources.get(resourceType) ?? [],
      resourceType,
    )
  }

  return {
    baseResourceNames: baseResourceTypes.map((resourceType) =>
      pascalCase(resourceType),
    ),
    schemas: [...schemas.values()],
  }
}

const createResourceObjectProperty = (
  property: Property,
  baseName: string,
  addSchema: (name: string, properties: Property[], baseName: string) => void,
): ResourceObjectProperty => {
  const referenceName = pascalCase(`${baseName}_${property.name}`)

  if (property.format === 'object') {
    const properties = documentedProperties(property.properties)

    if (properties.length > 0) {
      addSchema(referenceName, properties, baseName)
      return { name: property.name, kind: 'objectReference', referenceName }
    }
  }

  if (property.format === 'list') {
    const itemProperties =
      property.itemFormat === 'object'
        ? documentedProperties(property.itemProperties)
        : property.itemFormat === 'discriminated_object'
          ? mergeProperties(
              property.variants.map((variant) =>
                documentedProperties(variant.properties),
              ),
            )
          : []

    if (itemProperties.length > 0) {
      addSchema(referenceName, itemProperties, baseName)
      return { name: property.name, kind: 'listReference', referenceName }
    }
  }

  return {
    name: property.name,
    kind: 'value',
    phpType: getPhpType(property),
  }
}

const documentedProperties = (properties: Property[]): Property[] =>
  properties.filter((property) => !property.isUndocumented)

const mergeProperties = (propertyLists: Property[][]): Property[] => {
  const merged = new Map<string, Property>()

  for (const properties of propertyLists) {
    for (const property of properties) {
      const existing = merged.get(property.name)

      if (existing == null) {
        merged.set(property.name, property)
        continue
      }

      if (existing.format === 'object' && property.format === 'object') {
        merged.set(property.name, {
          ...existing,
          properties: mergeProperties([
            existing.properties,
            property.properties,
          ]),
        })
      }
    }
  }

  return [...merged.values()]
}
