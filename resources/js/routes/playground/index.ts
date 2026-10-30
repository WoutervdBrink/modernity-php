import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
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

const playground = {
    analyze: Object.assign(analyze, analyze),
}

export default playground