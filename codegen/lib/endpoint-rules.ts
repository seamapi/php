// TEMPORARY: Verbatim port of @seamapi/nextlove-sdk-generator
// lib/endpoint-rules.ts. This list only preserves legacy generated output:
// the ignored paths reproduce the previous endpoint filtering used when
// building the parent-to-child resource map.
// TODO: Delete this file once generated output is allowed to change; filter
// on blueprint undocumented flags instead.

export const ignoredEndpointPaths = [
  '/health',
  '/health/get_health',
  '/health/get_service_health',
  '/health/service/[service_name]',
]
