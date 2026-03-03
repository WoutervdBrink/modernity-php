#Test
Description: The mixed type was added in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
class foo { private mixed $bar; }
function foo(mixed $bar) {}
function bar(): mixed {}
?>