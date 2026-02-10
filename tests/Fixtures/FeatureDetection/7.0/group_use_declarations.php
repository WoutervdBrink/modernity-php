#Test
Description: Support for grouping use declarations has been added in PHP 7.0 (multiple classes).
Parser: 7.0
Min: 7.0

<?php
use some\namespace\{ClassA, ClassB, ClassC as C};
?>


#Test
Description: Support for grouping use declarations has been added in PHP 7.0 (multiple functions).
Parser: 7.0
Min: 7.0

<?php
use function some\namespace\{fn_a, fn_b, fn_c};
?>


#Test
Description: Support for grouping use declarations has been added in PHP 7.0 (multiple constants).
Parser: 7.0
Min: 7.0

<?php
use const some\namespace\{ConstA, ConstB, ConstC};
?>