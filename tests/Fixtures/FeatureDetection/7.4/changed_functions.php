#Test
Description: Changed functions in PHP 7.4 - functionality that is possible from 7.4.
Parser: 7.4
Min: 7.4
EveryLine: true

<?php
preg_replace_callback($pattern, $callback, $subject, $limit, $count, $flags);
preg_replace_callback_array($pattern, $subject, $limit, $count, $flags);
strip_tags($str, ['a', 'p']);
array_merge();
array_merge_recursive();
proc_open(['echo', 'Hello'], $descriptors, $pipes);
password_hash('secret', null);
password_needs_rehash('secret', null);
?>


#Test
Description: Changed functions in PHP 7.4 - functionality that is no longer possible in 7.4.
Parser: 7.4
Max: 7.3
EveryLine: true

<?php
mb_ereg_replace(65, 'A', 'test');
?>
