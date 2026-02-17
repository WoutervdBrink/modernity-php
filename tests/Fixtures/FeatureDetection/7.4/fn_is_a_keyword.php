#Test
Description: As of PHP 7.4, fn cannot be used to name classes.
Parser: 7.3
Max: 7.3

<?php
class fn {}
?>


#Test
Description: As of PHP 7.4, fn cannot be used to name interfaces.
Parser: 7.3
Max: 7.3

<?php
interface fn {}
?>


#Test
Description: As of PHP 7.4, fn cannot be used to name traits.
Parser: 7.3
Max: 7.3

<?php
trait fn {}
?>


#Test
Description: As of PHP 7.4, fn cannot be used to name functions.
Parser: 7.3
Max: 7.3

<?php
function fn() {}
?>
