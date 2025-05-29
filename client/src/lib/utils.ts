export function hasPermission(
  resource: string,
  operation: string,
  permissions: { resource: string; operation: string }[],
): boolean {
  return permissions.some((perm) => perm.resource === resource && perm.operation === operation)
}

export function hasAllPermissions(
  required: { resource: string; operation: string }[],
  userPermissions: { resource: string; operation: string }[],
): boolean {
  return required.every((req) =>
    userPermissions.some(
      (perm) => perm.resource === req.resource && perm.operation === req.operation,
    ),
  )
}

export function canRead(
  resource: string,
  userPermissions: { resource: string; operation: string }[],
): boolean {
  return userPermissions.some((perm) => perm.resource === resource && perm.operation === 'read')
}
