#Test
Description: Support for attributes on non-class constants was added in PHP 8.5.
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
#[Test] const FOO = 1;
#[Foo, Bar] const FOO = 2;
?>