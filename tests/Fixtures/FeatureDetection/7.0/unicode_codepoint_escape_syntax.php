#Test
Description: The unicode codepoint escape syntax was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
echo "\u{aa}".PHP_EOL;
?>
