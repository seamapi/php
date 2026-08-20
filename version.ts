import { readFile, writeFile } from 'node:fs/promises'
import { fileURLToPath } from 'node:url'

import { $ } from 'execa'

const versionFile = './src/Version.php'

const versionPattern = /public const VERSION = "[^"]*";/

const main = async (): Promise<void> => {
  const version = await injectVersion(
    fileURLToPath(new URL(versionFile, import.meta.url)),
  )
  // eslint-disable-next-line no-console
  console.log(`✓ Version ${version} injected into ${versionFile}`)

  const { command } = await $`git add ${versionFile}`
  // eslint-disable-next-line no-console
  console.log(`✓ Staged with '${command}'`)
}

const injectVersion = async (path: string): Promise<string> => {
  const { version } = await readPackageJson()

  if (version == null) {
    throw new Error('Missing version in package.json')
  }

  const data = (await readFile(path)).toString()

  if (!versionPattern.test(data)) {
    throw new Error(`Could not find the version constant in ${versionFile}`)
  }

  await writeFile(
    path,
    data.replace(versionPattern, `public const VERSION = "${version}";`),
  )

  return version
}

const readPackageJson = async (): Promise<{ version?: string }> => {
  const pkgBuff = await readFile(
    fileURLToPath(new URL('package.json', import.meta.url)),
  )
  return JSON.parse(pkgBuff.toString())
}

await main()
