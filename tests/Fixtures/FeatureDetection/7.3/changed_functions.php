#Test
Description: Changed functions in PHP 7.3 - functionality that is possible from 7.3.
Parser: 7.3
Min: 7.3
EveryLine: true

<?php
bscale(null);
bscale();
setcookie($name, $value, []);
setrawcookie($name, $value, []);
array_push($arr);
array_unshift($arr);
session_set_cookie_params([]);
?>
