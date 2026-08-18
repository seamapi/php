import type { Property } from '@seamapi/blueprint'

const formatKey = (property: Property): string =>
  property.format === 'list' ? `list<${property.itemFormat}>` : property.format

const isScalar = (property: Property): boolean =>
  property.format !== 'list' && property.format !== 'object'

interface MergedDocs {
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

// Each variant documents a property for its own case, which is accurate there
// but not for the single class the variants merge into. Some are merely narrow
// ("Previous code configuration" on a shape that also covers names); others
// contradict each other outright ("the error is not a device error" against
// "the error is a device error"). No description beats a wrong one, so keep one
// only when every variant that documents the property agrees.
const mergeDocs = (occurrences: Property[]): MergedDocs => {
  const descriptions = [
    ...new Set(
      occurrences
        .map((occurrence) => occurrence.description.trim())
        .filter((description) => description !== ''),
    ),
  ]
  const deprecated = occurrences.find(({ isDeprecated }) => isDeprecated)

  return {
    description: descriptions.length === 1 ? (descriptions[0] ?? '') : '',
    // Deprecating in any variant deprecates the merged property, so a warning
    // is never dropped just because another variant omits it.
    isDeprecated: deprecated != null,
    deprecationMessage: deprecated?.deprecationMessage ?? '',
  }
}

// The variants of a discriminated union collapse into a single class, so a
// property carried by more than one variant has to end up with every field any
// variant gives it. Keeping only the first occurrence silently drops the rest,
// which loses data once the merged shape is a typed class rather than a hash.
const mergeOccurrences = (occurrences: Property[], path: string): Property => {
  const [first, ...rest] = occurrences
  if (first == null) throw new Error(`Nothing to merge at ${path}.`)
  if (rest.length === 0) return first

  const docs = mergeDocs(occurrences)

  const formats = new Set(occurrences.map(formatKey))
  if (formats.size > 1) {
    // Scalars all become a plain accessor, so any of them represents the rest.
    if (occurrences.every(isScalar)) return { ...first, ...docs }
    throw new Error(
      `Cannot merge ${path}: variants disagree on its shape (${[...formats].join(', ')}).`,
    )
  }

  if (first.format === 'boolean') {
    const booleans = occurrences as Array<
      Extract<Property, { format: 'boolean' }>
    >
    const values = booleans.some(({ values }) => values == null)
      ? undefined
      : [...new Set(booleans.flatMap(({ values }) => values ?? []))]
    const merged = { ...first, ...docs }
    if (values == null) delete merged.values
    else merged.values = values
    return merged
  }

  if (first.format === 'object') {
    return {
      ...first,
      ...docs,
      properties: mergeProperties(
        occurrences.map(
          (occurrence) => (occurrence as typeof first).properties,
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
        occurrences.map(
          (occurrence) => (occurrence as typeof first).itemProperties,
        ),
        `${path}[]`,
      ),
    }
  }

  if (first.format === 'list' && first.itemFormat === 'discriminated_object') {
    // Keep every variant. Whoever consumes this list merges them in turn.
    return {
      ...first,
      ...docs,
      variants: occurrences.flatMap(
        (occurrence) => (occurrence as typeof first).variants,
      ),
    }
  }

  return { ...first, ...docs }
}

export const mergeProperties = (
  propertyLists: Property[][],
  path = '',
): Property[] => {
  const occurrences = new Map<string, Property[]>()
  for (const properties of propertyLists) {
    for (const property of properties) {
      const group = occurrences.get(property.name)
      if (group == null) {
        occurrences.set(property.name, [property])
      } else {
        group.push(property)
      }
    }
  }

  return [...occurrences.entries()]
    .map(([name, group]) =>
      mergeOccurrences(group, path === '' ? name : `${path}.${name}`),
    )
    .sort((a, b) => a.name.localeCompare(b.name))
}
