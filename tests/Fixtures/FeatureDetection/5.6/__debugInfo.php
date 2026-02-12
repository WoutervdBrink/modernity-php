#Test
Description: The __debugInfo() magic method was introduced in PHP 5.6.
Parser: 5.6
Min: 5.6

<?php
class Foo {
    function __debugInfo() {
        //
    }
}
?>

#Test
Description: A class without __debugInfo() does not require PHP 5.6.
Parser: 5.6
Min: none

<?php
class Foo {
    function bar() {

    }
}
?>