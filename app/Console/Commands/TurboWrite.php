<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\HandlesIO;
use App\Console\Commands\Traits\HandlesParser;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use App\Turbo\TurboWriter;
use LaravelZero\Framework\Commands\Command;
use PhpParser\NodeTraverserInterface;
use PhpParser\ParserFactory;

final class TurboWrite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'turbo:write
                            {--parser=latest : The PHP version to use when parsing, or \'latest\' for the latest supported version.}
                            {--O|overwrite : Overwrite the output file if it already exists.}
                            {input : The input file to parse, e.g. test.php}
                            {output : The output file to write to, e.g. test.turbo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a PHP file, detect its version constraints, and write the output as a Turbo file.';

    use HandlesIO, HandlesParser;

    /**
     * Execute the console command.
     */
    public function handle(ParserFactory $factory, NodeTraverserInterface $traverser): void
    {
        $input = $this->getInput();
        $output = $this->getOutputPath();
        $parser = $this->getRequestedParser($factory);

        if ($input === false || $output === false || $parser === false) {
            return;
        }

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($turbo = new TurboWriter);

        $stmts = $parser->parse($input) ?? [];

        $traverser->traverse($stmts);

        $turbo->write($output);
    }
}
