<?php

namespace App\Http\Controllers;

use App\Contracts\Analysis\ModernityAnalyzer;
use App\Data\Playground\AnalyzePlaygroundSourceRequestData;
use App\Data\Playground\AnalyzePlaygroundSourceResponseData;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class PlaygroundController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Playground', []);
    }

    public function analyze(AnalyzePlaygroundSourceRequestData $data, ModernityAnalyzer $analyzer): AnalyzePlaygroundSourceResponseData
    {
        try {
            $analysis = $analyzer->analyze($data->source);

            return AnalyzePlaygroundSourceResponseData::from([
                'analysis' => $analysis,
            ]);
        } catch (Throwable $e) {
            return AnalyzePlaygroundSourceResponseData::from([
                'error' => $e->getMessage().PHP_EOL.$e->getTraceAsString(),
            ]);
        }
    }
}
