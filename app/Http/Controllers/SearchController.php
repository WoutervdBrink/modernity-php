<?php

namespace App\Http\Controllers;

use App\Actions\Search\CreateSearch;
use App\Data\Search\CreateSearchRequestData;
use App\Data\Search\SearchData;
use App\Models\Search;
use App\Services\Git\LocalRepositoryStorage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class SearchController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Search/Index', [
            'searches' => SearchData::collect(Search::query()->orderByDesc('created_at')->get()),
        ]);
    }

    public function store(CreateSearchRequestData $data, CreateSearch $createSearch): RedirectResponse
    {
        $search = $createSearch($data->parameters);

        return redirect()
            ->route('searches.show', $search);
    }

    public function create(): Response
    {
        return Inertia::render('Search/Create', []);
    }

    public function show(Search $search): Response
    {
        $search->load('results', 'results.repository')->loadCount('results', 'acceptedResults');

        return Inertia::render('Search/Show', [
            'current_commit' => LocalRepositoryStorage::getCurrentApplicationCommitHash(),
            'search' => SearchData::from($search),
        ]);
    }
}
