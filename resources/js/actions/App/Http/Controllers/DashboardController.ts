import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DashboardController::index
* @see app/Http/Controllers/DashboardController.php:19
* @route '/'
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

const DashboardController = { index, queuePendingProcesses, queueFailedProcesses }

export default DashboardController