// Builds the template context for src/SeamClient.php: the SeamClient class
// with its parent client properties. The resource client classes themselves
// are generated into src/Routes, one file per client.

import type { PhpClient } from '../class-model.js'

const routesNamespace = 'Seam\\Routes'

export interface SeamClientLayoutContext {
  useStatements: string[]
  parentClients: Array<{ clientName: string; namespace: string }>
}

export const setSeamClientLayoutContext = (
  clients: PhpClient[],
): SeamClientLayoutContext => {
  const parentClients = clients
    .filter((c) => c.isParentClient)
    .map((c) => ({ clientName: c.clientName, namespace: c.namespace }))

  return {
    useStatements: parentClients
      .map((c) => `${routesNamespace}\\${c.clientName}Client`)
      .sort((a, b) => a.localeCompare(b)),
    parentClients,
  }
}
