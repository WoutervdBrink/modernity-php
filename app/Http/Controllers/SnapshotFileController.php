<?php

namespace App\Http\Controllers;

use App\Data\Repository\RepositoryData;
use App\Data\Snapshot\SnapshotData;
use App\Data\SnapshotFile\SnapshotFileData;
use App\Models\Enums\ContentEncoding;
use App\Models\SnapshotFile;
use Inertia\Inertia;
use Inertia\Response;

final class SnapshotFileController extends Controller
{
    public function show(SnapshotFile $snapshotFile): Response
    {
        $snapshotFile->load('sourceFile', 'snapshot', 'snapshot.repository');

        return Inertia::render(
            'SnapshotFile/Show',
            [
                'snapshotFile' => SnapshotFileData::from($snapshotFile),
                'snapshot' => SnapshotData::from($snapshotFile->snapshot),
                'repository' => RepositoryData::from($snapshotFile->snapshot?->repository),
                'contents' => [
                    'encoding' => $snapshotFile->sourceFile?->content_encoding ?? ContentEncoding::PLAIN,
                    'contents' => $snapshotFile->sourceFile?->contents ?? '',
                ],
                'analysis' => $snapshotFile->sourceFile?->analysis,
            ]
        );
    }
}
