#Test
Description: As of PHP 7.1, void cannot be used to name classes.
Parser: 7.0
Max: 7.0

<?php
class void {}
?>


#Test
Description: As of PHP 7.1, void cannot be used to name interfaces.
Parser: 7.0
Max: 7.0

<?php
interface void {}
?>


#Test
Description: As of PHP 7.1, void cannot be used to name traits.
Parser: 7.0
Min: 5.4
Max: 7.0

<?php
trait void {}
?>


#Test
Description: As of PHP 7.1, iterable cannot be used to name classes.
Parser: 7.0
Max: 7.0

<?php
class iterable {}
?>


#Test
Description: As of PHP 7.1, iterable cannot be used to name interfaces.
Parser: 7.0
Max: 7.0

<?php
interface iterable {}
?>


#Test
Description: As of PHP 7.1, iterable cannot be used to name traits.
Parser: 7.0
Min: 5.4
Max: 7.0

<?php
trait iterable {}
?>
