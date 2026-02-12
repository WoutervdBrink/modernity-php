#Test
Description: Variables bound to a closure via the use construct cannot use the same name as any superglobals, $this, or any parameter.
Parser: 7.1
Max: 7.0
EveryLine: true

<?php
$foo = function () use ($_SERVER) {};
$foo = function () use ($this) {};
$foo = function ($param) use ($param) {};
?>