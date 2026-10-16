import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
export const show = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/snapshots/{snapshot}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
show.url = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { snapshot: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { snapshot: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            snapshot: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        snapshot: typeof args.snapshot === 'object'
        ? args.snapshot.id
        : args.snapshot,
    }

    return show.definition.url
            .replace('{snapshot}', parsedArgs.snapshot.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
show.get = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
show.head = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
const showForm = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
showForm.get = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SnapshotController::show
* @see app/Http/Controllers/SnapshotController.php:20
* @route '/snapshots/{snapshot}'
*/
showForm.head = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\SnapshotController::analyze
* @see app/Http/Controllers/SnapshotController.php:46
* @route '/snapshots/{snapshot}/analyze'
*/
export const analyze = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: analyze.url(args, options),
    method: 'post',
})

analyze.definition = {
    methods: ["post"],
    url: '/snapshots/{snapshot}/analyze',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SnapshotController::analyze
* @see app/Http/Controllers/SnapshotController.php:46
* @route '/snapshots/{snapshot}/analyze'
*/
analyze.url = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { snapshot: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { snapshot: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            snapshot: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        snapshot: typeof args.snapshot === 'object'
        ? args.snapshot.id
        : args.snapshot,
    }

    return analyze.definition.url
            .replace('{snapshot}', parsedArgs.snapshot.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SnapshotController::analyze
* @see app/Http/Controllers/SnapshotController.php:46
* @route '/snapshots/{snapshot}/analyze'
*/
analyze.post = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: analyze.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SnapshotController::analyze
* @see app/Http/Controllers/SnapshotController.php:46
* @route '/snapshots/{snapshot}/analyze'
*/
const analyzeForm = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: analyze.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SnapshotController::analyze
* @see app/Http/Controllers/SnapshotController.php:46
* @route '/snapshots/{snapshot}/analyze'
*/
analyzeForm.post = (args: { snapshot: number | { id: number } } | [snapshot: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: analyze.url(args, options),
    method: 'post',
})

analyze.form = analyzeForm

const SnapshotController = { show, analyze }

export default SnapshotController