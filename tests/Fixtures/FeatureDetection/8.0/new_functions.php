#Test
Description: New functions in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
get_resource_id();
DateTime::createFromInterface();
DateTimeImmutable::createFromInterface();
enchant_dict_add();
enchant_dict_is_added();
ldap_count_references();
openssl_cms_encrypt();
openssl_cms_decrypt();
openssl_cms_read();
openssl_cms_sign();
openssl_cms_verify();
preg_last_error_msg();
str_contains();
str_starts_with();
str_ends_with();
fdiv();
get_debug_type();
PhpToken::tokenize();
ZipArchive::setMtimeName();
ZipArchive::setMtimeIndex();
ZipArchive::registerProgressCallback();
ZipArchive::registerCancelCallback();
ZipArchive::replaceFile();
ZipArchive::isCompressionMethodSupported();
ZipArchive::isEncryptionMethodSupported();
ZipArchive::getStatusString();
imagegetinterpolation();
?>