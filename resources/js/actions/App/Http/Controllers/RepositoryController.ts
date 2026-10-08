import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/repositories',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::index
* @see app/Http/Controllers/RepositoryController.php:18
* @route '/repositories'
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
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
export const show = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/repositories/{repository}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
show.url = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { repository: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { repository: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            repository: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        repository: typeof args.repository === 'object'
        ? args.repository.id
        : args.repository,
    }

    return show.definition.url
            .replace('{repository}', parsedArgs.repository.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
show.get = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
show.head = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
const showForm = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
showForm.get = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\RepositoryController::show
* @see app/Http/Controllers/RepositoryController.php:65
* @route '/repositories/{repository}'
*/
showForm.head = (args: { repository: number | { id: number } } | [repository: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const RepositoryController = { index, show }

export default RepositoryController