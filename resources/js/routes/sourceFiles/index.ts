import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sourceFiles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::index
* @see app/Http/Controllers/SourceFileController.php:19
* @route '/sourceFiles'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
export const show = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/sourceFiles/{sourceFile}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
show.url = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sourceFile: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'sha256' in args) {
        args = { sourceFile: args.sha256 }
    }

    if (Array.isArray(args)) {
        args = {
            sourceFile: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        sourceFile: typeof args.sourceFile === 'object'
        ? args.sourceFile.sha256
        : args.sourceFile,
    }

    return show.definition.url
            .replace('{sourceFile}', parsedArgs.sourceFile.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
show.get = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
show.head = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
const showForm = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
showForm.get = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SourceFileController::show
* @see app/Http/Controllers/SourceFileController.php:66
* @route '/sourceFiles/{sourceFile}'
*/
showForm.head = (args: { sourceFile: string | { sha256: string } } | [sourceFile: string | { sha256: string } ] | string | { sha256: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const sourceFiles = {
    index: Object.assign(index, index),
    show: Object.assign(show, show),
}

export default sourceFiles