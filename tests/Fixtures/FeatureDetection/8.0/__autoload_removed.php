#Test
Description: The __autoload() autoloader functionality was removed in PHP 8.0
Parser: 8.0
Max: 7.4

<?php
function __autoload() {}
?>