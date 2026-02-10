#Test
Description: Scalar return type declarations were introduced in PHP 7.0 (string).
Parser: 7.0
Min: 7.0

<?php
function foo(): string {}
?>


#Test
Description: Scalar return type declarations were introduced in PHP 7.0 (int).
Parser: 7.0
Min: 7.0

<?php
function foo(): int {}
?>


#Test
Description: Scalar return type declarations were introduced in PHP 7.0 (float).
Parser: 7.0
Min: 7.0

<?php
function foo(): float {}
?>


#Test
Description: Scalar return type declarations were introduced in PHP 7.0 (bool).
Parser: 7.0
Min: 7.0

<?php
function foo(): bool {}
?>
