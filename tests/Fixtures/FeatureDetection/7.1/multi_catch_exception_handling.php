#Test
Description: A catch with multiple exceptions was introduced in PHP 7.1
Parser: 7.1
Min: 7.1

<?php
try {} catch (foo|bar $e) {}
?>