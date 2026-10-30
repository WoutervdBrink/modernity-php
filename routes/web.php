<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FailedJobsController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SnapshotController;
use App\Http\Controllers\SnapshotFileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/queuePendingProcesses', [DashboardController::class, 'queuePendingProcesses'])->name('queuePendingProcesses');
Route::post('/queueFailedProcesses', [DashboardController::class, 'queueFailedProcesses'])->name('queueFailedProcesses');

Route::get('/failedJobs', [FailedJobsController::class, 'index'])->name('failedJobs');

Route::get('/searches', [SearchController::class, 'index'])->name('searches.index');
Route::get('/searches/create', [SearchController::class, 'create'])->name('searches.create');
Route::post('/searches', [SearchController::class, 'store'])->name('searches.store');
Route::get('/searches/{search}', [SearchController::class, 'show'])->name('searches.show');

Route::get('/repositories', [RepositoryController::class, 'index'])->name('repositories.index');
Route::get('/repositories/{repository}', [RepositoryController::class, 'show'])->name('repositories.show');
Route::post('/repositories/{repository}/discoverSnapshots', [RepositoryController::class, 'discoverSnapshots'])->name('repositories.discoverSnapshots');

Route::get('/snapshots/{snapshot}', [SnapshotController::class, 'show'])->name('snapshots.show');
Route::post('/snapshots/{snapshot}/analyze', [SnapshotController::class, 'analyze'])->name('snapshots.analyze');

Route::get('/snapshotFiles/{snapshotFile}', [SnapshotFileController::class, 'show'])->name('snapshotFiles.show');
