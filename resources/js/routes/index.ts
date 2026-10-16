import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::__invoke
* @see app/Http/Controllers/DashboardController.php:9
* @route '/'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
export const failedJobs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: failedJobs.url(options),
    method: 'get',
})

failedJobs.definition = {
    methods: ["get","head"],
    url: '/failedJobs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
failedJobs.url = (options?: RouteQueryOptions) => {
    return failedJobs.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
failedJobs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: failedJobs.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
failedJobs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: failedJobs.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
const failedJobsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: failedJobs.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
failedJobsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: failedJobs.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\FailedJobsController::failedJobs
* @see app/Http/Controllers/FailedJobsController.php:12
* @route '/failedJobs'
*/
failedJobsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: failedJobs.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

failedJobs.form = failedJobsForm
