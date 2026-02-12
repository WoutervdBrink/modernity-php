#Test
Description: The long2ip function expects an integer as of PHP 7.1
Parser: 7.1
Min: 7.1

<?php
long2ip(1);
?>


#Test
Description: The long2ip function no longer expects a string as of PHP 7.1
Parser: 7.1
Max: 7.0

<?php
long2ip('127.0.0.1');
?>
