#Test
Description: New functions in PHP 7.1
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
sapi_windows_cp_get();
sapi_windows_cp_set();
sapi_windows_cp_conv();
sapi_windows_cp_is_utf8();
Closure::fromCallable();
curl_multi_errno();
curl_share_errno();
curl_share_strerror();
openssl_get_curve_names();
session_create_id();
session_gc();
is_iterable();
pcntl_async_signals();
pcntl_signal_get_handler();
?>