#Test
Description: The integer octal literal prefix (0o...) was added in PHP 8.1.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
echo 0o1;
echo 0O1;
?>