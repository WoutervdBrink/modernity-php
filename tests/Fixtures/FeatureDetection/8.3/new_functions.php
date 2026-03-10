#Test
Description: New functions in PHP 8.3
Parser: 8.3
Min: 8.3
EveryLine: true

<?php
DatePeriod::createFromISO8601String();
IntlGregorianCalendar::createFromDate();
IntlGregorianCalendar::createFromDateTime();
json_validate();
ldap_connect_wallet();
ldap_exop_sync();
mb_str_pad();
posix_sysconf();
posix_pathconf();
posix_fpathconf();
posix_eaccess();
pg_set_error_context_visibility();
ReflectionMethod::createFromMethodName();
socket_atmark();
str_increment();
str_decrement();
stream_context_set_options();
?>