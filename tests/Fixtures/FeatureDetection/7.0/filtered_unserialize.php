#Test
Description: The allowed_classes option was added to unserialize() in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
unserialize($foo, ["allowed_classes" => false]);
?>
