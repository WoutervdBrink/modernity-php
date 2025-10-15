#Test
Description: The use operator was extended to support importing constants in PHP 5.6.
Parser: 5.6
Min: 5.6

<?php
use const Foo\BAR;
?>

#Test
Description: The use operator was extended to support importing functions in PHP 5.6.
Parser: 5.6
Min: 5.6

<?php
use function Foo\bar;
?>
