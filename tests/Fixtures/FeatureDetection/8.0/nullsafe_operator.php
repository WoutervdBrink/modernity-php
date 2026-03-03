#Test
Description: The nullsafe method and property operators were added in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
$foo?->bar();
echo $foo?->bar;
?>