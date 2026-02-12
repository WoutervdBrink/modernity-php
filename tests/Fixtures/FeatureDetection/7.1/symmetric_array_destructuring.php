#Test
Description: As of PHP 7.1, the shorthand array syntax can be used to destructure arrays.
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
[$foo, $bar] = $baz;
foreach ($foo as [$bar, $baz]) {}
?>