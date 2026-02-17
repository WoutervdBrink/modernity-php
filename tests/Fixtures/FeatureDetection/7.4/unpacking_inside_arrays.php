#Test
Description: Unpacking inside arrays was added in PHP 7.4
Parser: 7.4
Min: 7.4

<?php
$parts = ['apple', 'pear'];
$fruits = ['banana', 'orange', ...$parts, 'watermelon'];
?>