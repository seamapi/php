import { dirname, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

import {
  generatePhpSDK as generateSdk,
  writeFs,
} from '@seamapi/nextlove-sdk-generator'
import { openapi } from '@seamapi/types/connect'
import { deleteAsync } from 'del'

const libDirName = 'src'

const rootPath = dirname(fileURLToPath(import.meta.url))
const outputPath = resolve(rootPath, libDirName)

await deleteAsync(outputPath)

const fileSystem = await generateSdk({
  openApiSpecObject: openapi,
})

const files = Object.entries(fileSystem).filter(([fileName]) =>
  fileName.startsWith(`${libDirName}/`),
)

writeFs(rootPath, Object.fromEntries(files))
