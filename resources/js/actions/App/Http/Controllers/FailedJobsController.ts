import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/failedJobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::index
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
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

const FailedJobsController = { index }

export default FailedJobsController