<?php

namespace App\Http\Controllers;

use App\Data\Repository\RepositoryData;
use App\Data\Snapshot\SnapshotData;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class RepositoryController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $repositories = QueryBuilder::for(Repository::query()->withAccepted())
            ->allowedFilters(
                AllowedFilter::scope('snapshots_discovered'),
                AllowedFilter::scope('accepted')
            )
            ->allowedSorts(
                'name',
                'description',
                'created_at',
            )
            ->defaultSort('name')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->paginate()
            ->withQueryString();

        return Inertia::render('Repository/Index', [
            'repositories' => RepositoryData::collect($repositories, PaginatedDataCollection::class),
            'filters' => [
                [
                    'key' => 'filter[snapshots_discovered]',
                    'label' => 'Snapshots discovered',
                    'options' => [
                        ['value' => 1, 'text' => 'Yes'],
                        ['value' => 0, 'text' => 'No'],
                    ],
                ],
                [
                    'key' => 'filter[accepted]',
                    'label' => 'Accepted',
                    'options' => [
                        ['value' => '1', 'text' => 'Yes'],
                        ['value' => '0', 'text' => 'No'],
                    ],
                ],
            ],
        ]);
    }

    public function show(Repository $repository, Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $snapshots = QueryBuilder::for($repository->snapshots())
            ->allowedFilters(
                AllowedFilter::scope('downloaded')
            )
            ->allowedSorts(
                'tag',
                'commit_sha',
                'committed_at',
            )
            ->defaultSort('tag')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('tag', 'like', '%'.$search.'%')
                        ->orWhere('commit_sha', 'like', '%'.$search.'%');
                });
            })
            ->paginate()
            ->withQueryString();

        return Inertia::render('Repository/Show', [
            'repository' => RepositoryData::from($repository),
            'snapshots' => SnapshotData::collect($snapshots, PaginatedDataCollection::class),
            'filters' => [
                [
                    'key' => 'filter[downloaded]',
                    'label' => 'Downloaded',
                    'options' => [
                        ['value' => 1, 'text' => 'Yes'],
                        ['value' => 0, 'text' => 'No'],
                    ],
                ],
            ],
        ]);
    }
}
