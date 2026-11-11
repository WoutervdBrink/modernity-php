<?php

namespace App\Http\Controllers;

use App\Data\SnapshotFile\SnapshotFileData;
use App\Data\SourceFile\SourceFileData;
use App\Models\Enums\ContentEncoding;
use App\Models\SourceFile;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class SourceFileController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $repositories = QueryBuilder::for(SourceFile::query())
            ->allowedFilters(
                AllowedFilter::scope('exception_thrown'),
                AllowedFilter::scope('analyzed')
            )
            ->allowedSorts(
                'sha256',
                'size',
                'created_at',
            )
            ->defaultSort('sha256')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('sha256', 'like', '%'.$search.'%');
                });
            })
            ->paginate()
            ->withQueryString();

        return Inertia::render('SourceFile/Index', [

            'sourceFiles' => SourceFileData::collect($repositories, PaginatedDataCollection::class),
            'filters' => [
                [
                    'key' => 'filter[exception_thrown]',
                    'label' => 'Exception thrown',
                    'options' => [
                        ['value' => 1, 'text' => 'Yes'],
                        ['value' => 0, 'text' => 'No'],
                    ],
                ],
                [
                    'key' => 'filter[analyzed]',
                    'label' => 'Analyzed',
                    'options' => [
                        ['value' => '1', 'text' => 'Yes'],
                        ['value' => '0', 'text' => 'No'],
                    ],
                ],
            ],
        ]);
    }

    public function show(SourceFile $sourceFile, Request $request): Response
    {
        $search = trim($request->string('search')->toString());

        $snapshotFiles = QueryBuilder::for($sourceFile->snapshotFiles()->getQuery())
            ->allowedFilters()
            ->allowedSorts(
                'sha256',
                'path',
                'created_at',
            )
            ->defaultSort('sha256')
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('sha256', 'like', '%'.$search.'%')
                        ->orWhere('path', 'like', '%'.$search.'%');
                });
            })
            ->with(['snapshot', 'snapshot.repository'])
            ->paginate()
            ->withQueryString();

        return Inertia::render('SourceFile/Show', [
            'files' => SnapshotFileData::collect($snapshotFiles, PaginatedDataCollection::class),
            'sourceFile' => SourceFileData::from($sourceFile),
            'contents' => [
                'encoding' => $sourceFile->content_encoding ?? ContentEncoding::PLAIN,
                'contents' => $sourceFile->contents ?? '',
            ],
            'analysis' => $sourceFile->analysis,
        ]);
    }
}
