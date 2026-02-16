#Test
Description: As of PHP 7.0, globally reserved words are allowed as property, constant, and method names.
Parser: 7.0
Min: 7.0
EveryLine: true

<?php
class foo { function new() {} }
trait bar { function new() {} }
interface baz { function new(); }
?>