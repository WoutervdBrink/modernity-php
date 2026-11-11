<?php

namespace App\Http\Controllers;

use App\Contracts\Analysis\ModernityAnalyzer;
use App\Data\Playground\AnalyzePlaygroundSourceRequestData;
use App\Data\Playground\AnalyzePlaygroundSourceResponseData;
use App\Data\SourceFile\SourceFileContentsData;
use App\Models\SourceFile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class PlaygroundController extends Controller
{
    public function index(Request $request): Response
    {
        /** @var SourceFile|null $sourceFile */
        $sourceFile = SourceFile::find($request->string('file'));

        return Inertia::render('Playground', [
            'contents' => $sourceFile !== null ? SourceFileContentsData::from($sourceFile) : null,
        ]);
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
                'error' => $e->__toString(),
            ]);
        }
    }
}
