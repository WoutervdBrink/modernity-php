#Test
Description: The match expression was added in PHP 8.0
Parser: 8.0
Min: 8.0

<?php
match ($foo) {
    'bar' => 'baz',
    default => 'bar',
};
?>