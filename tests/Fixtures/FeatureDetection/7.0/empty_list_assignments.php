#Test
Description: As of PHP 7.0, list() constructs can no longer be empty.
Parser: 5.6
Max: 5.6

<?php
list() = $a;
?>

#Test
Description: As of PHP 7.0, list() constructs can no longer be empty.
Parser: 5.6
Max: 5.6

<?php
list(,,) = $a;
?>

#Test
Description: As of PHP 7.0, list() constructs can no longer be empty.
Parser: 5.6
Max: 5.6

<?php
list($x, list(), $y) = $a;
?>