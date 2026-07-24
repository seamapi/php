import { dirname } from 'node:path'
import { fileURLToPath } from 'node:url'

import layouts from '@metalsmith/layouts'
import { createBlueprint } from '@seamapi/blueprint'
import { getHandlebarsPartials } from '@seamapi/smith'
import * as types from '@seamapi/types/connect'
import { deleteAsync } from 'del'
import Metalsmith from 'metalsmith'

import { helpers, routes } from './lib/index.js'

const rootDir = dirname(fileURLToPath(import.meta.url))

await Promise.all([deleteAsync(['./src/Objects', './src/SeamClient.php'])])

const partials = await getHandlebarsPartials(`${rootDir}/layouts/partials`)

// Generate the blueprint with undocumented endpoints, resources, parameters,
// and properties already omitted, so the codegen only sees the public API.
const blueprint = await createBlueprint({ ...types }, { omitUndocumented: true })

Metalsmith(rootDir)
  .source('./content')
  .destination('../')
  .clean(false)
  .metadata({ blueprint })
  .use(routes)
  .use(
    layouts({
      default: 'default.hbs',
      engineOptions: {
        noEscape: true,
        helpers,
        partials,
      },
    }),
  )
  .build((err) => {
    if (err != null) throw err
  })
