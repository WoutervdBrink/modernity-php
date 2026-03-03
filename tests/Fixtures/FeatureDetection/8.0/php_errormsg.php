#Test
Description: The $php_errormsg variable will never be filled as of PHP 8.0.
Parser: 7.4
Max: 7.4
EveryLine: true

<?php
echo $php_errormsg;
?>