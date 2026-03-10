#Test
Description: The SensitiveParameter attribute was added in PHP 8.2.
Parser: 8.2
Min: 8.2

<?php
function foo(#[SensitiveParameter] $bar) {}
?>