#Test
Description: Support for AEAD in ext/openssl was added in PHP 7.1.
Parser: 7.1
Min: 7.1
EveryLine: true

<?php
openssl_encrypt('foo', 'bar', 'baz', 0, 'iv', $tag, 'aad');
openssl_decrypt('foo', 'bar', 'baz', 0, 'iv', $tag, 'aad');
?>