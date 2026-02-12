#Test
Description: Support for keys in list() was introduced in PHP 7.1
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
list('foo' => $foo, 'bar' => $bar) = $baz;
['foo' => $foo, 'bar' => $bar] = $baz;
?>