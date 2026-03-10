#Test
Description: New functions in PHP 8.4
Parser: 8.4
Min: 8.4
EveryLine: true

<?php
request_parse_body();
bcceil();
bcdivmod();
bcfloor();
bcround();
DateTime::createFromTimestamp();
DateTimeImmutable::createFromTimestamp();
DOMXPath::quote();
grapheme_str_split();
mb_trim();
mb_ltrim();
mb_rtrim();
mb_ucfirst();
mb_lcfirst();
opcache_jit_blacklist();
pcntl_getcpu();
pcntl_getcpuaffinity();
pcntl_getqos_class();
pcntl_setns();
pcntl_waitid();
pg_change_password();
pg_jit();
pg_put_copy_data();
pg_put_copy_end();
pg_result_memory_size();
pg_set_chunked_rows_size();
pg_socket_poll();
sodium_crypto_aead_aegis128l_keygen();
sodium_crypto_aead_aegis128l_encrypt();
sodium_crypto_aead_aegis128l_decrypt();
sodium_crypto_aead_aegis256_keygen();
sodium_crypto_aead_aegis256_decrypt();
sodium_crypto_aead_aegis256_encrypt();
http_get_last_response_headers();
http_clear_last_response_headers();
fpow();
array_all();
array_any();
array_find();
array_find_key();
XMLReader::fromStream();
XMLReader::fromUri();
XMLReader::fromString();
XMLWriter::toStream();
XMLWriter::toUri();
XMLWriter::toMemory();
?>