#Test
Description: As of PHP 7.0, switch statements cannot have multiple default blocks.
Parser: 7.0
Max: 5.6

<?php
switch ($a) {
    default: foo(); break;
    default: bar(); break;
}
?>