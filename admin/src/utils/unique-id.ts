let guid = Date.now()
export function uniqueId(prefix = '') {
    prefix = prefix ? `${prefix}_` : ''
    return `${prefix}${(guid++).toString(36).toLowerCase()}`
}
