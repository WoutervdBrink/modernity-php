#Test
Description: Changed functions in PHP 8.0 - functionality that is no longer possible in 8.0.
Parser: 8.0
Max: 7.4
EveryLine: true

<?php
mktime();
gmmktime();
mb_parse_str($str);
mb_strrpos($hay, $needle, 'UTF-8');
odbc_exec($obdc, $query, $flags);
openssl_open($data, $output, $key, $private);
openssl_seal($data, $sealed, $keys, $key);
pg_connect("host", "port", "options", "tty", "dbname");
assert('$a == $b');
parse_str($string);
vsprintf($format, '');
vfprintf($stream, $format, '');
vprintf($format, '');
password_hash('test', 'hash', array('salt' => 'foo'));
crypt('test');
sem_get($key, $max_acquire, $permissions, 2);
curl_version($version);
?>


#Test
Description: Changed functions in PHP 8.0 - functionality that is possible as of 8.0.
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
imagepolygon($im, $points, $color);
imageopenpolygon($im, $points, $color);
imagefilledpolygon($im, $points, $color);
?>