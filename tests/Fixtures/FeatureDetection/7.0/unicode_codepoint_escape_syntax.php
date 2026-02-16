#Test
Description: The unicode codepoint escape syntax was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
echo "\u{aa}".PHP_EOL;
?>


#Test
Description: Invalid unicode codepoint escapes cause fatal errors in PHP 7.0.
Parser: 5.6
Max: 5.6
EveryLine: true

<?php
echo "\u{deadbeef}".PHP_EOL;
echo "\u{abcghi}".PHP_EOL;
echo "\u{}".PHP_EOL;
echo "\u{hmm}".PHP_EOL;
echo "\u{1234567}".PHP_EOL;
?>
