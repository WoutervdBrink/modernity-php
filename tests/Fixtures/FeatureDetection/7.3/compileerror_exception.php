#Test
Description: The CompileError exception was introduced in PHP 7.3.
Parser: 7.3
Min: 7.3

<?php
try {

} catch (CompileError $e) {}
?>