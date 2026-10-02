import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/searches',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::index
* @see app/Http/Controllers/SearchController.php:16
* @route '/searches'
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
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/searches/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::create
* @see app/Http/Controllers/SearchController.php:31
* @route '/searches/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\SearchController::store
* @see app/Http/Controllers/SearchController.php:23
* @route '/searches'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/searches',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SearchController::store
* @see app/Http/Controllers/SearchController.php:23
* @route '/searches'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SearchController::store
* @see app/Http/Controllers/SearchController.php:23
* @route '/searches'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SearchController::store
* @see app/Http/Controllers/SearchController.php:23
* @route '/searches'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SearchController::store
* @see app/Http/Controllers/SearchController.php:23
* @route '/searches'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
export const show = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/searches/{search}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
show.url = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { search: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { search: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            search: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        search: typeof args.search === 'object'
        ? args.search.id
        : args.search,
    }

    return show.definition.url
            .replace('{search}', parsedArgs.search.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
show.get = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
show.head = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
const showForm = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
showForm.get = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\SearchController::show
* @see app/Http/Controllers/SearchController.php:36
* @route '/searches/{search}'
*/
showForm.head = (args: { search: number | { id: number } } | [search: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const searches = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
}

export default searches