import { dirname } from 'node:path'
import { fileURLToPath } from 'node:url'

import layouts from '@metalsmith/layouts'
import { blueprint, getHandlebarsPartials } from '@seamapi/smith'
import * as types from '@seamapi/types/connect'
import { deleteAsync } from 'del'
import Metalsmith from 'metalsmith'

import { helpers, routes } from './lib/index.js'

const rootDir = dirname(fileURLToPath(import.meta.url))

await Promise.all([deleteAsync(['./src/Objects', './src/SeamClient.php'])])

const partials = await getHandlebarsPartials(`${rootDir}/layouts/partials`)

Metalsmith(rootDir)
  .source('./content')
  .destination('../')
  .clean(false)
  // Omit undocumented endpoints, resources, parameters, and properties so the
  // codegen only sees the public API.
  .use(blueprint({ types, omitUndocumented: true }))
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
