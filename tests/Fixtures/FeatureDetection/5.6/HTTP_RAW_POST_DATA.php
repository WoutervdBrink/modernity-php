#Test
Description: Accessing $HTTP_RAW_POST_DATA was deprecated in PHP 5.6.
Parser: 5.6
Max: 5.6

<?php
var_dump($HTTP_RAW_POST_DATA);
?>