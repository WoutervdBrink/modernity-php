import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
export const show = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/snapshotFiles/{snapshotFile}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
show.url = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { snapshotFile: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { snapshotFile: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            snapshotFile: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        snapshotFile: typeof args.snapshotFile === 'object'
        ? args.snapshotFile.id
        : args.snapshotFile,
    }

    return show.definition.url
            .replace('{snapshotFile}', parsedArgs.snapshotFile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
show.get = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
show.head = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
const showForm = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
showForm.get = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotFileController::show
* @see app/Http/Controllers/SnapshotFileController.php:14
* @route '/snapshotFiles/{snapshotFile}'
*/
showForm.head = (args: { snapshotFile: number | { id: number } } | [snapshotFile: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const snapshotFiles = {
    show: Object.assign(show, show),
}

export default snapshotFiles