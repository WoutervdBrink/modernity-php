#Test
Description: As of PHP 8.3, it is possible to use arbitrary expressions as the default value of a static variable.
Parser: 8.3
Min: 8.3
EveryLine: true

<?php
function bar() { static $bar = 3 + 4 + baz(); } // static variable
?>
