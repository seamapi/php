// Merges the property lists of a discriminated union into one list.
//
// Two things in the blueprint are unions the SDK collapses into a single
// class: the event and action attempt resources, which the blueprint models as
// one resource per variant, and any property that is a list of discriminated
// objects.
//
// This logic is shared with the Ruby and Python SDKs. Changing the semantics
// here means changing them there too, or the SDKs drift.

import type { Property } from '@seamapi/blueprint'

/**
 * Unions property lists by name, recursively.
 *
 * A name carried by one variant is taken as is. A name carried by several is
 * merged: objects and lists of objects recurse, lists of discriminated objects
 * concatenate their variants for whoever consumes the list to merge in turn,
 * and scalars collapse onto one another because they map to the same PHP type.
 */
export const mergeProperties = (
  propertyLists: Property[][],
  path = '',
): Property[] => {
  const occurrencesByName = new Map<string, Property[]>()

  for (const properties of propertyLists) {
    for (const property of properties) {
      const occurrences = occurrencesByName.get(property.name)
      if (occurrences == null) {
        occurrencesByName.set(property.name, [property])
        continue
      }
      occurrences.push(property)
    }
  }

  return [...occurrencesByName.entries()].map(([name, occurrences]) =>
    mergeOccurrences(occurrences, path === '' ? name : `${path}.${name}`),
  )
}

const mergeOccurrences = (occurrences: Property[], path: string): Property => {
  const [first] = occurrences

  if (first == null) {
    throw new Error(`Cannot merge ${path}: no occurrences`)
  }

  if (occurrences.length === 1) return first

  // Each variant documents the property for its own case, which is accurate
  // there and not necessarily on the class the variants collapse into. Keep
  // the text only when every variant that documents it agrees, since no
  // documentation beats wrong documentation.
  const docs = mergeDocs(occurrences)

  const formatKeys = new Set(occurrences.map(getFormatKey))

  if (formatKeys.size > 1) {
    if (occurrences.every(isScalar)) {
      // Differing scalar formats map to the same PHP type, so any one will do.
      return { ...first, ...docs }
    }

    throw new Error(
      `Cannot merge ${path}: variants disagree on its shape (${[...formatKeys].sort().join(', ')})`,
    )
  }

  if (first.format === 'object') {
    return {
      ...first,
      ...docs,
      properties: mergeProperties(
        occurrences.map((occurrence) =>
          occurrence.format === 'object' ? occurrence.properties : [],
        ),
        path,
      ),
    }
  }

  if (first.format === 'list' && first.itemFormat === 'object') {
    return {
      ...first,
      ...docs,
      itemProperties: mergeProperties(
        occurrences.map((occurrence) =>
          occurrence.format === 'list' && occurrence.itemFormat === 'object'
            ? occurrence.itemProperties
            : [],
        ),
        path,
      ),
    }
  }

  if (first.format === 'list' && first.itemFormat === 'discriminated_object') {
    return {
      ...first,
      ...docs,
      variants: occurrences.flatMap((occurrence) =>
        occurrence.format === 'list' &&
        occurrence.itemFormat === 'discriminated_object'
          ? occurrence.variants
          : [],
      ),
    }
  }

  return { ...first, ...docs }
}

interface MergedDocs {
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

const mergeDocs = (occurrences: Property[]): MergedDocs => {
  const descriptions = [
    ...new Set(
      occurrences
        .map((occurrence) => occurrence.description.trim())
        .filter((description) => description !== ''),
    ),
  ]

  // Deprecating a property in any variant deprecates it on the merged class:
  // first wins could silently undeprecate a field depending on the order the
  // blueprint happens to list the variants in.
  const deprecated = occurrences.find((occurrence) => occurrence.isDeprecated)

  return {
    description: descriptions.length === 1 ? (descriptions[0] ?? '') : '',
    isDeprecated: deprecated != null,
    deprecationMessage: deprecated?.deprecationMessage ?? '',
  }
}

// A list of objects and a list of scalars are different shapes, so the item
// format is part of the key.
const getFormatKey = (property: Property): string =>
  property.format === 'list' ? `list<${property.itemFormat}>` : property.format

const isScalar = (property: Property): boolean =>
  property.format !== 'list' && property.format !== 'object'
