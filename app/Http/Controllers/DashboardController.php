<?php

namespace App\Http\Controllers;

use App\Contracts\Git\ApplicationCommitSource;
use App\Jobs\QueuePendingProcesses;
use App\Models\Enums\ProcessStatus;
use App\Models\Repository;
use App\Models\Search;
use App\Models\Snapshot;
use App\Models\SnapshotFile;
use App\Models\SourceFile;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function index(ApplicationCommitSource $commitSource): Response
    {
        return Inertia::render('Dashboard', [
            'application_commit' => $commitSource->getApplicationCommit(),
            'statistics' => [
                'searches' => [
                    'total' => Search::count(),
                    'byStatus' => Search::getProcessStatusCounts(),
                ],
                'repositories' => [
                    'total' => Repository::count(),
                    'accepted' => Repository::accepted(true)->count(),
                    'rejected' => Repository::accepted(false)->count(),
                    'byStatus' => Repository::getProcessStatusCounts(Repository::accepted(true)->getQuery()),
                ],
                'snapshots' => [
                    'total' => Snapshot::count(),
                    'withSemver' => Snapshot::whereNotNull('semver')->count(),
                    'withoutSemver' => Snapshot::whereNull('semver')->count(),
                    'byStatus' => Snapshot::getProcessStatusCounts(Snapshot::whereNotNull('semver')->getQuery()),
                ],
                'files' => [
                    'snapshot' => SnapshotFile::count(),
                    'source' => SourceFile::count(),
                ],
            ],
        ]);
    }

    public function queuePendingProcesses(): RedirectResponse
    {
        QueuePendingProcesses::dispatch(ProcessStatus::PENDING);

        return redirect()
            ->route('dashboard');
    }

    public function queueFailedProcesses(): RedirectResponse
    {
        QueuePendingProcesses::dispatch(ProcessStatus::FAILED);

        return redirect()
            ->route('dashboard');
    }
}
