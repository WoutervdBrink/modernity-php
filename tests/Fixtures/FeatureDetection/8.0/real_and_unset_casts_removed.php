#Test
Description: The (real) and (unset) casts were removed in PHP 8.0.
Parser: 7.4
Max: 7.4
EveryLine: true

<?php
$foo = (real) $foo;
(unset) $foo;
?>