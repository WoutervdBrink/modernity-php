#Test
Description: Simple constants were always allowed
Parser: 5.6
Min: none

<?php
const A = 5;
?>

#Test
Description: Simple class constants were always allowed
Parser: 5.6
Min: none

<?php
class Foo
{
    const B = 5;
}
?>

#Test
Description: Expressions in constants were introduced in PHP 5.6
Parser: 5.6
Min: 5.6

<?php
const C = 5 * 3;
?>

#Test
Description: Expressions in class constants were introduced in PHP 5.6
Parser: 5.6
Min: 5.6

<?php
class Bar
{
    const D = 5 * 3;
}
?>