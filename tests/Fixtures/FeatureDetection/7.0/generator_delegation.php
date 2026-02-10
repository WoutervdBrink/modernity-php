#Test
Description: Support for delegating to other generators has been added in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
function foo() {
    yield from bar();
}
?>
