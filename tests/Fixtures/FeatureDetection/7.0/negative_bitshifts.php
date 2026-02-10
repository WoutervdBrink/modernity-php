#Test
Description: As of PHP 7.0, bitwise shifts by negative numbers will now throw an ArithmeticError. #1
Parser: 5.6
Max: 5.6

<?php
echo 1 >> -1;
?>

#Test
Description: As of PHP 7.0, bitwise shifts by negative numbers will now throw an ArithmeticError. #2
Parser: 5.6
Max: 5.6

<?php
$val >>= -1;
?>

#Test
Description: As of PHP 7.0, bitwise shifts by negative numbers will now throw an ArithmeticError. #3
Parser: 5.6
Max: 5.6

<?php
echo 1 << -1;
?>

#Test
Description: As of PHP 7.0, bitwise shifts by negative numbers will now throw an ArithmeticError. #4
Parser: 5.6
Max: 5.6

<?php
$val <<= -1;
?>
