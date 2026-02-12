#Test
Description: The $this variable can no longer be used as a variable name or reassigned in PHP 7.1.
Parser: 7.1
Max: 7.0
EveryLine: true

<?php
$this = true;
$this += 3;
function foo($this) {}
global $this;
?>