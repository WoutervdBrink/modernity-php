#Test
Description: Named arguments were added in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
foo(bar: $baz);
foo(array: $baz);
$foo->bar(baz: $foo);
$foo->bar(array: $baz);
?>