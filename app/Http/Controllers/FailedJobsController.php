<?php

namespace App\Http\Controllers;

use App\Data\FailedJobData;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

final class FailedJobsController extends Controller
{
    public function index(): Response
    {
        $jobs = FailedJobData::collect(
            DB::table('failed_jobs')
                ->select('*')
                ->orderByDesc('failed_at')
                ->get()
        );

        return Inertia::render('FailedJob/Index', [
            'jobs' => $jobs,
        ]);
    }
}
