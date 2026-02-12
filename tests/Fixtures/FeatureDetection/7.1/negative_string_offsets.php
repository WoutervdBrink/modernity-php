#Test
Description: Negative string offsets were introduced in PHP 7.1
Parser: 7.1
Min: 7.1

<?php
echo 'foo'[-1];
?>