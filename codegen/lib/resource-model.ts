// Builds the resource class model for src/Resources from the blueprint.
//
// Each blueprint resource becomes a PHP class in its own file. Nested object
// properties and lists of objects are split into their own classes, named
// after the base resource and the property, e.g. the device battery property
// becomes DeviceBattery. Those classes only exist to type a resource
// property, so they are emitted as local classes in the file of the resource
// that introduced them. Discriminated unions (events, action attempts, and
// discriminated object lists) are flattened into a single class with the
// union of the variant properties.

import type { Blueprint, Property } from '@seamapi/blueprint'
import { pascalCase } from 'change-case'

import { getPhpType } from './map-php-type.js'

export type ResourceClassProperty =
  | ({ kind: 'value'; phpType: string } & ResourceClassPropertyMetadata)
  | ({ kind: 'objectReference'; referenceName: string } & ResourceClassPropertyMetadata)
  | ({ kind: 'listReference'; referenceName: string } & ResourceClassPropertyMetadata)

interface ResourceClassPropertyMetadata {
  name: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

export interface ResourceClassSchema {
  name: string
  description: string
  isDeprecated: boolean
  deprecationMessage: string
  properties: ResourceClassProperty[]
}

export interface ResourceSchema {
  name: string
  resourceClass: ResourceClassSchema
  localClasses: ResourceClassSchema[]
}

export interface ResourceModel {
  resourceNames: string[]
  resources: ResourceSchema[]
}

export const createResourceModel = (blueprint: Blueprint): ResourceModel => {
  const baseResources = new Map<string, Property[]>()

  for (const resource of blueprint.resources) {
    baseResources.set(resource.resourceType, resource.properties)
  }

  // The blueprint models events and action attempts as one resource per
  // variant. The PHP SDK has a single class for each, so the variants are
  // merged into one schema.
  const { events } = blueprint
  if (events.length > 0) {
    baseResources.set(
      'event',
      mergeProperties(events.map((event) => event.properties)),
    )
  }

  const { actionAttempts } = blueprint
  if (actionAttempts.length > 0) {
    baseResources.set(
      'action_attempt',
      mergeProperties(
        actionAttempts.map((actionAttempt) => actionAttempt.properties),
      ),
    )
  }

  const classes = new Map<string, ResourceClassSchema>()
  const localClassNames = new Map<string, string[]>()

  let currentResourceName = ''

  const addClass = (
    name: string,
    properties: Property[],
    baseName: string,
    description = '',
    isDeprecated = false,
    deprecationMessage = '',
  ): void => {
    if (classes.has(name)) return
    const schema: ResourceClassSchema = {
      name,
      description,
      isDeprecated,
      deprecationMessage,
      properties: [],
    }
    classes.set(name, schema)
    if (name !== currentResourceName) {
      localClassNames.get(currentResourceName)?.push(name)
    }
    schema.properties = properties.map((property) =>
      createResourceClassProperty(property, baseName, addClass),
    )
  }

  const baseResourceTypes = [...baseResources.keys()].sort()
  const resources = baseResourceTypes.map((resourceType) => {
    const name = pascalCase(resourceType)
    currentResourceName = name
    localClassNames.set(name, [])
    const sourceResource =
      blueprint.resources.find((resource) => resource.resourceType === resourceType) ??
      (resourceType === 'event' ? blueprint.events[0] : blueprint.actionAttempts[0])
    addClass(
      name,
      baseResources.get(resourceType) ?? [],
      resourceType,
      sourceResource?.description,
      sourceResource?.isDeprecated,
      sourceResource?.deprecationMessage,
    )

    const resourceClass = classes.get(name)
    if (resourceClass == null) {
      throw new Error(
        `Missing class for resource ${resourceType}: ${name} is already used by a property class of another resource`,
      )
    }

    return {
      name,
      resourceClass,
      localClasses: (localClassNames.get(name) ?? [])
        .map((localClassName) => {
          const localClass = classes.get(localClassName)
          if (localClass == null) {
            throw new Error(`Missing local class ${localClassName}`)
          }
          return localClass
        })
        .sort((a, b) => a.name.localeCompare(b.name)),
    }
  })

  return {
    resourceNames: resources.map((resource) => resource.name),
    resources,
  }
}

const createResourceClassProperty = (
  property: Property,
  baseName: string,
  addClass: (
    name: string,
    properties: Property[],
    baseName: string,
    description?: string,
    isDeprecated?: boolean,
    deprecationMessage?: string,
  ) => void,
): ResourceClassProperty => {
  const referenceName = pascalCase(`${baseName}_${property.name}`)
  const metadata = {
    name: property.name,
    description: property.description,
    isDeprecated: property.isDeprecated,
    deprecationMessage: property.deprecationMessage,
  }

  if (property.format === 'object') {
    const { properties } = property

    if (properties.length > 0) {
      addClass(referenceName, properties, baseName, property.description)
      return { ...metadata, kind: 'objectReference', referenceName }
    }
  }

  if (property.format === 'list') {
    const itemProperties =
      property.itemFormat === 'object'
        ? property.itemProperties
        : property.itemFormat === 'discriminated_object'
          ? mergeProperties(
              property.variants.map((variant) => variant.properties),
            )
          : []

    if (itemProperties.length > 0) {
      addClass(referenceName, itemProperties, baseName, property.description)
      return { ...metadata, kind: 'listReference', referenceName }
    }
  }

  return {
    ...metadata,
    kind: 'value',
    phpType: getPhpType(property),
  }
}

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
