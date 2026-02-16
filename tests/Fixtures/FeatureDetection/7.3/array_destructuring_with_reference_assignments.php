#Test
Description: As of PHP 7.3, array destructuring supports reference assignments.
Parser: 7.3
Min: 7.3
EveryLine: true

<?php
[&$foo] = $bar;
[$a, [&$foo]] = $bar;
list(&$foo) = $bar;
?>