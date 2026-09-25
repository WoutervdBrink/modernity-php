<?php

namespace App\Console\Commands;

use App\Console\Commands\Traits\HandlesIO;
use App\Console\Commands\Traits\HandlesParser;
use App\Parser\Visitors\ASTToXMLConvertingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use LaravelZero\Framework\Commands\Command;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;

final class ConvertAST extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ast:convert
                            {--parser=latest : The PHP version to use when parsing, or \'latest\' for the latest supported version.}
                            {--O|overwrite : Overwrite the output file if it already exists.}
                            {input : The input file to parse, e.g. test.php}
                            {output : The output file to write to, e.g. test.ast.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse a PHP file and convert the AST to XML';

    use HandlesIO, HandlesParser;

    /**
     * Execute the console command.
     */
    public function handle(ParserFactory $factory, NodeTraverser $traverser): void
    {
        $input = $this->getInput();
        $output = $this->getOutputPath();
        $parser = $this->getRequestedParser($factory);

        if ($input === false || $output === false || $parser === false) {
            return;
        }

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($xml = new ASTToXMLConvertingVisitor);

        $stmts = $parser->parse($input) ?? [];

        $traverser->traverse($stmts);

        $out = $xml->document->saveXML();

        file_put_contents($output, $out);

        $this->info('Written XML output to '.$output);
    }
}
