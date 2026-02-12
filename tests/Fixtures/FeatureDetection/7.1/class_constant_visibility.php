#Test
Description: Support for specifying the visibility of class constants was introduced in PHP 7.1
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
class foo { public const bar = 1; }
class bar { private const bar = 1; }
class baz { protected const bar = 1; }
?>