<?php

namespace App\Inspectors;

use App\Language\PhpVersionConstraint;
use PhpParser\Node;

interface Inspector
{
    /**
     * @template T of Node
     *
     * @param  T  $node
     */
    public static function inspect(Node $node): PhpVersionConstraint;
}
