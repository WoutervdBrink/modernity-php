#Test
Description: New functions in PHP 8.5
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
get_error_handler();
get_exception_handler();
Closure::getCurrent();
curl_multi_get_handles();
curl_share_init_persistent();
enchant_dict_remove_from_session();
enchant_dict_remove();
Locale::isRightToLeft();
locale_is_right_to_left();
grapheme_levenshtein();
opcache_is_script_cached_in_file_cache();
pg_close_stmt();
pg_service();
array_first();
array_last();
?>