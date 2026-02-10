#Test
Description: As of PHP 7.0, octal literals containing invalid numbers will cause a parse error.
Parser: 5.6
Max: 5.6

<?php
$val = 0128;
?>