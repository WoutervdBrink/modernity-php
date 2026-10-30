import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
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
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::dashboard
* @see app/Http/Controllers/DashboardController.php:19
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
* @see \App\Http\Controllers\DashboardController::queuePendingProcesses
* @see app/Http/Controllers/DashboardController.php:48
* @route '/queuePendingProcesses'
*/
export const queuePendingProcesses = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: queuePendingProcesses.url(options),
    method: 'post',
})

queuePendingProcesses.definition = {
    methods: ["post"],
    url: '/queuePendingProcesses',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DashboardController::queuePendingProcesses
* @see app/Http/Controllers/DashboardController.php:48
* @route '/queuePendingProcesses'
*/
queuePendingProcesses.url = (options?: RouteQueryOptions) => {
    return queuePendingProcesses.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::queuePendingProcesses
* @see app/Http/Controllers/DashboardController.php:48
* @route '/queuePendingProcesses'
*/
queuePendingProcesses.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: queuePendingProcesses.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DashboardController::queuePendingProcesses
* @see app/Http/Controllers/DashboardController.php:48
* @route '/queuePendingProcesses'
*/
const queuePendingProcessesForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: queuePendingProcesses.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DashboardController::queuePendingProcesses
* @see app/Http/Controllers/DashboardController.php:48
* @route '/queuePendingProcesses'
*/
queuePendingProcessesForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: queuePendingProcesses.url(options),
    method: 'post',
})

queuePendingProcesses.form = queuePendingProcessesForm

/**
* @see \App\Http\Controllers\DashboardController::queueFailedProcesses
* @see app/Http/Controllers/DashboardController.php:56
* @route '/queueFailedProcesses'
*/
export const queueFailedProcesses = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: queueFailedProcesses.url(options),
    method: 'post',
})

queueFailedProcesses.definition = {
    methods: ["post"],
    url: '/queueFailedProcesses',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DashboardController::queueFailedProcesses
* @see app/Http/Controllers/DashboardController.php:56
* @route '/queueFailedProcesses'
*/
queueFailedProcesses.url = (options?: RouteQueryOptions) => {
    return queueFailedProcesses.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::queueFailedProcesses
* @see app/Http/Controllers/DashboardController.php:56
* @route '/queueFailedProcesses'
*/
queueFailedProcesses.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: queueFailedProcesses.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DashboardController::queueFailedProcesses
* @see app/Http/Controllers/DashboardController.php:56
* @route '/queueFailedProcesses'
*/
const queueFailedProcessesForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: queueFailedProcesses.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DashboardController::queueFailedProcesses
* @see app/Http/Controllers/DashboardController.php:56
* @route '/queueFailedProcesses'
*/
queueFailedProcessesForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: queueFailedProcesses.url(options),
    method: 'post',
})

queueFailedProcesses.form = queueFailedProcessesForm

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
