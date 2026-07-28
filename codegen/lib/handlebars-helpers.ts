export const identity = (x: unknown): unknown => x

export interface DeprecatedPhpDocContext {
  description: string
  isDeprecated: boolean
  deprecationMessage: string
}

export interface MethodPhpDocContext extends DeprecatedPhpDocContext {
  returnType: string
  responseDescription: string
  parameters: Array<{ name: string; type: string; description: string }>
}

export const resourcePhpDoc = (context: DeprecatedPhpDocContext): string =>
  createPhpDoc(context.description, deprecatedTag(context))

export const hasPhpDoc = (context: DeprecatedPhpDocContext): boolean =>
  context.description.trim() !== '' || context.isDeprecated

export const propertyPhpDoc = (context: DeprecatedPhpDocContext): string =>
  createPhpDoc(context.description, deprecatedTag(context), '        ')

export const methodPhpDoc = (context: MethodPhpDocContext): string =>
  createPhpDoc(
    context.description,
    [
      ...context.parameters.map(
        (parameter) =>
          `@param ${parameter.type} $${parameter.name}${parameter.description === '' ? '' : ` ${parameter.description}`}`,
      ),
      `@return ${context.returnType}${context.responseDescription === '' ? '' : ` ${context.responseDescription}`}`,
      ...deprecatedTag(context),
    ],
    '    ',
  )

const deprecatedTag = (context: DeprecatedPhpDocContext): string[] =>
  context.isDeprecated
    ? [
        `@deprecated${context.deprecationMessage === '' ? '' : ` ${context.deprecationMessage}`}`,
      ]
    : []

const createPhpDoc = (
  description: string,
  tags: string[],
  indentation = '',
): string => {
  const descriptionLines = description
    .trim()
    .split(/\r?\n/)
    .map(sanitizePhpDocLine)
  const populatedDescription = description.trim() === '' ? [] : descriptionLines
  const populatedTags = tags.filter((tag) => tag.trim() !== '').map(sanitizePhpDocLine)
  const lines = [
    ...populatedDescription,
    ...(populatedDescription.length > 0 && populatedTags.length > 0 ? [''] : []),
    ...populatedTags,
  ]

  if (lines.length === 0) return ''

  return [
    `${indentation}/**`,
    ...lines.map((line) => `${indentation} *${line === '' ? '' : ` ${line}`}`),
    `${indentation} */`,
  ].join('\n')
}

const sanitizePhpDocLine = (line: string): string =>
  line.replaceAll('*/', '* /').trimEnd()
