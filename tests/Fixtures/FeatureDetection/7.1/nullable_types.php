#Test
Description: Nullable types were introduced in PHP 7.1
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
function foo(): ?string {}
function bar(?string $baz) {}
?>