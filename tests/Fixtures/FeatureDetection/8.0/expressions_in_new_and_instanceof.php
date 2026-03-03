#Test
Description: new and instanceof can be used with arbitrary expressions as of PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
new ($a.$b)(...$args);
$obj instanceof ('Foo'.'Bar');
?>