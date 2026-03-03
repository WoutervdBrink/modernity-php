#Test
Description: Applying the final modifier on a private method will produce a warning in PHP 8.0.
Parser: 8.0
Max: 7.4

<?php
class foo {
    private final function bar() {}
}
?>
