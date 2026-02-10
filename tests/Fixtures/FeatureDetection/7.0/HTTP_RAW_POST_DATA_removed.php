#Test
Description: As of PHP 7.0, the $HTTP_RAW_POST_DATA variable has been removed.
Parser: 7.0
Max: 5.6

<?php
var_dump($HTTP_RAW_POST_DATA);
?>