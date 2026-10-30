<?php

namespace App\Http\Controllers;

use App\Actions\Search\QueueSearch;
use App\Contracts\Git\ApplicationCommitSource;
use App\Data\Search\CreateSearchRequestData;
use App\Data\Search\SearchData;
use App\Models\Search;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

final class SearchController extends Controller
{
    public function __construct(private ApplicationCommitSource $commitSource) {}

    public function index(): Response
    {
        $searches = QueryBuilder::for(Search::query())
            ->allowedSorts(
                'created_at',
            )
            ->defaultSort('created_at')
            ->paginate()
            ->withQueryString();

        return Inertia::render('Search/Index', [
            'searches' => SearchData::collect($searches, PaginatedDataCollection::class),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(CreateSearchRequestData $data, QueueSearch $queueSearch): RedirectResponse
    {
        $search = Search::create($data->parameters, $this->commitSource->getApplicationCommit());

        $queueSearch($search);

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
            'current_commit' => $this->commitSource->getApplicationCommit(),
            'search' => SearchData::from($search),
        ]);
    }
}
