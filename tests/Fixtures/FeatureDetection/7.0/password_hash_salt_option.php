#Test
Description: The password_hash salt option was deprecated in PHP 7.0.
Parser: 7.0
Max: 5.6

<?php
password_hash('test', null, array('salt' => 'foo'));
?>
