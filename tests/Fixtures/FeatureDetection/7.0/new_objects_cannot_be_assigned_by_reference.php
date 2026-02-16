#Test
Description: As of PHP 7.0, the result of the new statement can no longer be assigned to a variable by reference.
Parser: 5.6
Max: 5.6

<?php
class foo {}
$foo =& new foo();
?>
