#Test
Description: As of PHP 8.1, it is possible to specify named arguments after an argument unpack.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
foo(...$args, named: $arg);
$foo->bar(...$args, named: $arg);
bar::baz(...$args, named: $arg);
?>