#Test
Description: Support for combining intersection and union types in DNF was added in PHP 8.2.
Parser: 8.2
Min: 8.2
EveryLine: true

<?php
function foo((P&Q)|R $bar) {}
function bar(): (P&Q)|R {}
class A { function foo((P&Q)|R $bar) {} }
class B { function bar(): (P&Q)|R {} }
?>