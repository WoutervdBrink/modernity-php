#Test
Description: As of PHP 7.0, it is no longer possible to define two or more function parameters with the same name.
Parser: 7.0
Max: 5.6

<?php
function foo($a, $a) {}
?>


#Test
Description: As of PHP 7.0, it is no longer possible to define two or more method parameters with the same name.
Parser: 7.0
Max: 5.6

<?php
class foo {
    public function foo($a, $a) {}
}
?>
