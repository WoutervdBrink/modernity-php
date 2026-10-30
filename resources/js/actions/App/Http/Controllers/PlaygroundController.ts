import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/playground',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PlaygroundController::index
* @see app/Http/Controllers/PlaygroundController.php:10
* @route '/playground'
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
* @see \App\Http\Controllers\PlaygroundController::analyze
* @see app/Http/Controllers/PlaygroundController.php:15
* @route '/playground/analyze'
*/
export const analyze = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: analyze.url(options),
    method: 'post',
})

analyze.definition = {
    methods: ["post"],
    url: '/playground/analyze',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PlaygroundController::analyze
* @see app/Http/Controllers/PlaygroundController.php:15
* @route '/playground/analyze'
*/
analyze.url = (options?: RouteQueryOptions) => {
    return analyze.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PlaygroundController::analyze
* @see app/Http/Controllers/PlaygroundController.php:15
* @route '/playground/analyze'
*/
analyze.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: analyze.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PlaygroundController::analyze
* @see app/Http/Controllers/PlaygroundController.php:15
* @route '/playground/analyze'
*/
const analyzeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: analyze.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PlaygroundController::analyze
* @see app/Http/Controllers/PlaygroundController.php:15
* @route '/playground/analyze'
*/
analyzeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: analyze.url(options),
    method: 'post',
})

analyze.form = analyzeForm

const PlaygroundController = { index, analyze }

export default PlaygroundController