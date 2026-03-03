#Test
Description: The static return type was added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
class Foo {
    function create(): static {
        return new static();
    }
}
?>