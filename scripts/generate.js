import { generatePhpSDK, writeFs } from "@seamapi/nextlove-sdk-generator"
import { openapi } from "@seamapi/types/connect"
import path from "node:path"
import { fileURLToPath } from "node:url"
import { deleteAsync } from "del"

const PHP_SDK_DIRECTORY_PREFIX = "src/"
const PROJECT_ROOT_PATH = path.resolve(
  path.dirname(fileURLToPath(import.meta.url)),
  "../",
)
const MAIN_SEAM_DIR_PATH = path.resolve(
  PROJECT_ROOT_PATH,
  PHP_SDK_DIRECTORY_PREFIX,
)

const main = async () => {
  try {
    await deleteAsync(MAIN_SEAM_DIR_PATH)

    const pythonSdkFileSystem = await generatePhpSDK({
      openApiSpecObject: openapi,
    })

    const seamFiles = Object.entries(pythonSdkFileSystem).filter(([fileName]) =>
      fileName.startsWith(PHP_SDK_DIRECTORY_PREFIX),
    )

    writeFs(PROJECT_ROOT_PATH, Object.fromEntries(seamFiles))

    console.log("PHP SDK generated successfully!")
  } catch (error) {
    console.error("Failed to generate SDK:", error)
  }
}

await main()
