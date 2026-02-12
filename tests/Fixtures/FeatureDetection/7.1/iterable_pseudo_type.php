#Test
Description: The iterable pseudo-type was introduced in PHP 7.1
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
function foo(iterable $baz) {}
function bar(): iterable {}
?>