<?php

namespace App\Commands;

use App\Parser\Visitors\ASTToXMLConvertingVisitor;
use App\Parser\Visitors\NodeFeatureConstraintDetectingVisitor;
use LaravelZero\Framework\Commands\Command;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use PhpParser\PhpVersion;

class ConvertAST extends Command
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

    private function ensureInput(string $input): bool
    {
        if (! file_exists($input) || ! is_readable($input)) {
            $this->error('Input file '.$input.' does not exist or is not readable');

            return false;
        }

        return true;
    }

    private function ensureOutput(string $output): bool
    {
        if (file_exists($output)) {
            if (! $this->option('overwrite') && ! $this->confirm('Output file '.$output.' exists. Overwrite?')) {
                return false;
            }
        }

        if (! touch($output) || ! is_writable($output)) {
            $this->error('Output file '.$output.' is not writable');

            return false;
        }

        return true;
    }

    /**
     * Execute the console command.
     */
    public function handle(ParserFactory $factory, NodeTraverser $traverser): void
    {
        $input = $this->argument('input');
        $output = $this->argument('output');

        if (! $this->ensureInput($input) || ! $this->ensureOutput($output)) {
            return;
        }

        $parser = $this->option('parser');
        $parser = $factory->createForVersion($parser === 'latest' ? PhpVersion::getNewestSupported() : PhpVersion::fromString($parser));

        $traverser->addVisitor(new NodeFeatureConstraintDetectingVisitor);
        $traverser->addVisitor($xml = new ASTToXMLConvertingVisitor);

        $stmts = $parser->parse(file_get_contents($input)) ?? [];

        $traverser->traverse($stmts);

        $out = $xml->document->saveXML();

        file_put_contents($output, $out);

        $this->info('Written XML output to '.$output);
    }
}
