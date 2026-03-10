#Test
Description: Changed functions in PHP 8.5 - functionality that is possible as of 8.5.
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
grapheme_strpos($haystack, $needle, $offset, $locale);
grapheme_stripos($haystack, $needle, $offset, $locale);
grapheme_strrpos($haystack, $needle, $offset, $locale);
grapheme_strripos($haystack, $needle, $offset, $locale);
grapheme_substr($string, $offset, $length, $locale);
grapheme_strstr($haystack, $needle, $beforeNeedle, $locale);
grapheme_stristr($haystack, $needle, $beforeNeedle, $locale);
ldap_get_option(null, $option, $value);
openssl_public_encrypt($data, $encrypted, $public, $padding, $algo);
openssl_private_encrypt($data, $decrypted, $private, $padding, $algo);
openssl_sign($data, $signature, $pviate, $algo, $padding);
openssl_verify($data, $signature, $public, $algo, $padding);
openssl_cms_encrypt($input, $output, $cert, $headers, $lfags, $encoding, 'algo');
pcntl_waitid($idtype, $id, $info, $flags, $usage);
?>
