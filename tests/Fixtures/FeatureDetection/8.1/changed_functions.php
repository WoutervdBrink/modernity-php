#Test
Description: Changed functions in PHP 8.1 - functionality that is possible as of 8.1.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
hash($algo, $data, $binary, $options);
hash_file($algo, $filename, $binary, $options);
hash_init($algo, $flags, $key, $options);
fputcsv($stream, $fields, $separator, $enclosure, $escape, $eol);
?>