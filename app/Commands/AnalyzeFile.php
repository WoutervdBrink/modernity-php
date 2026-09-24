<?php

namespace App\Commands;

use App\Commands\Traits\HandlesIO;
use App\Commands\Traits\HandlesParser;
use App\Modernity\ModernityDeterminizer;
use App\Parser\Visitors\ASTFeatureConstraintMergingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use LaravelZero\Framework\Commands\Command;
use PhpParser\NodeTraverserInterface;
use PhpParser\ParserFactory;

final class AnalyzeFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:file {file}
                            {--parser=latest : The PHP version to use when parsing, or \'latest\' for the latest supported version.}
                            {input : The input file to parse, e.g. test.php}';

    use HandlesIO, HandlesParser;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze a file to determine its modernity signature.';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(ParserFactory $factory, NodeTraverserInterface $traverser): void
    {
        $path = $this->getInputPath();
        $code = $this->getInput();
        $parser = $this->getRequestedParser($factory);

        if ($path === false || $code === false || $parser === false) {
            return;
        }

        $ast = $parser->parse($code) ?? [];

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($constraintVisitor = new ASTFeatureConstraintMergingVisitor);

        $traverser->traverse($ast);

        $constraint = $constraintVisitor->constraint;

        $vector = new ModernityDeterminizer()->determine($ast);

        $report = View::make('reports.modernity', compact('path', 'code', 'constraint', 'vector'))->render();

        $disk = Storage::disk('reports');
        $outPath = basename($path).'.modernity.html';

        if (! $disk->put($outPath, $report)) {
            $this->error('Unable to write report');
        } else {
            $this->info('Report written to '.$disk->path($outPath));
        }
    }
}
