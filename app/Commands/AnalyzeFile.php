<?php

namespace App\Commands;

use App\Modernity\ModernityDeterminizer;
use App\Parser\Visitors\ASTFeatureConstraintMergingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use LaravelZero\Framework\Commands\Command;
use LogicException;
use PhpParser\Node\Stmt;
use PhpParser\NodeTraverserInterface;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;
use RuntimeException;

class AnalyzeFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'analyze:file {file}
                            {--parser=latest : The PHP version to use when parsing, or \'latest\' for the latest supported version.}
                            {input : The input file to parse, e.g. test.php}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze a file to determine its modernity signature.';

    /**
     * @throws RuntimeException
     */
    private function getInputPath(): string
    {
        $path = $this->argument('input');

        if (! is_string($path)) {
            throw new RuntimeException('Invalid input file');
        }

        if (! file_exists($path) || ! is_readable($path)) {
            throw new RuntimeException('File "'.$path.'" does not exist or is not readable');
        }

        return $path;
    }

    /**
     * @throws RuntimeException
     */
    private function getInput(): string
    {
        $code = file_get_contents($this->getInputPath());

        if ($code === false) {
            throw new RuntimeException('Unable to read file "'.$this->getInputPath().'"');
        }

        return $code;
    }

    /**
     * @throws LogicException
     */
    private function getParserVersion(): PhpVersion
    {
        $requestedVersion = $this->option('parser');

        if (! is_string($requestedVersion) || empty($requestedVersion)) {
            $requestedVersion = 'latest';
        }

        return $requestedVersion === 'latest'
            ? PhpVersion::getNewestSupported()
            : PhpVersion::fromString($requestedVersion);
    }

    /**
     * @return Stmt[]
     *
     * @throws Exception
     */
    private function parse(string $input): array
    {
        try {
            $parser = $this->app->make(ParserFactory::class)->createForVersion($this->getParserVersion());
        } catch (BindingResolutionException) {
            throw new LogicException('Could not construct parser factory');
        }

        $stmts = $parser->parse($input);

        if ($stmts === null) {
            throw new RuntimeException('Unknown error parsing input file');
        }

        return $stmts;
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(NodeTraverserInterface $traverser): void
    {
        $path = $this->getInputPath();
        $code = $this->getInput();
        $ast = $this->parse($code);

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
