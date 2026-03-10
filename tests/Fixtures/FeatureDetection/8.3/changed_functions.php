#Test
Description: Changed functions in PHP 8.3 - functionality that is possible as of 8.3.
Parser: 8.3
Min: 8.3
EveryLine: true

<?php
posix_getrlimit($resource);
odbc_autocommit($odbc, null);
pg_fetch_result($result, null, $field);
pg_field_prtlen($result, null, $field_name_or_number);
pg_field_is_null($result, null, $field);
mt_srand(null);
srand(null);
strrchr($haystack, $needle, $before_needle);
?>


#Test
Description: Changed functions in PHP 8.3 - functionality that is no longer possible as of 8.3.
Parser: 8.2
Max: 8.2
EveryLine: true

<?php
imagerotate($im, $angle, $bg, $ignore);
?>
