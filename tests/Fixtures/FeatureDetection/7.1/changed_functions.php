#Test
Description: Changed functions in PHP 7.1 - functionality that is possible from 7.1.
Parser: 7.1
Min: 7.1
PerLine: true

<?php
getopt(1, 2, 3);
getenv();
get_headers(1, 2, 3);
unpack(1, 2, 3);
pg_last_notice(1, 2);
pg_fetch_all(1, 2);
pg_select(1, 2, 3, 4);
?>
