#Test
Description: Intersection types were added in PHP 8.1.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
function foo(P&Q $bar) {}
function bar(): P&Q {}
class A { function foo(P&Q $bar) {} }
class B { function bar(): P&Q {} }
?>