<?php

namespace App\Http\Controllers;

use App\Data\Repository\RepositoryData;
use App\Data\Snapshot\SnapshotData;
use App\Data\SnapshotFile\SnapshotFileData;
use App\Jobs\AnalyzeSnapshot;
use App\Models\Snapshot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\QueryBuilder;

final class SnapshotController extends Controller
{
    public function show(Snapshot $snapshot, Request $request): Response
    {
        $search = $request->string('search');

        $files = QueryBuilder::for($snapshot->files()->with('sourceFile')->getQuery())
            ->allowedSorts('path', 'sha256')
            ->defaultSort('path')
            ->when($search->isNotEmpty(), function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('path', 'like', '%'.$search.'%')
                        ->orWhere('sha256', 'like', '%'.$search.'%');
                });
            })
            ->paginate()
            ->withQueryString();

        return Inertia::render(
            'Snapshot/Show',
            [
                'repository' => RepositoryData::from($snapshot->repository),
                'snapshot' => SnapshotData::from($snapshot),
                'files' => SnapshotFileData::collect($files, PaginatedDataCollection::class),
            ]
        );
    }

    public function analyze(Snapshot $snapshot): RedirectResponse
    {
        AnalyzeSnapshot::dispatch($snapshot);

        return redirect()
            ->route('snapshots.show', $snapshot);
    }
}
