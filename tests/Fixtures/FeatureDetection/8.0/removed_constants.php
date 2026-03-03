#Test
Description: Removed constants in PHP 8.0.
Parser: 8.0
Max: 7.4
EveryLine: true

<?php
echo FILTER_FLAG_SCHEME_REQUIRED;
echo FILTER_FLAG_HOST_REQUIRED;
echo INPUT_REQUEST;
echo INPUT_SESSION;
echo INTL_IDNA_VARIANT_2003;
echo Normalizer::NONE;
echo MB_OVERLOAD_MAIL;
echo MB_OVERLOAD_STRING;
echo MB_OVERLOAD_REGEX;
echo FILTER_SANITIZE_MAGIC_QUOTES;
echo ZipArchive::OPSYS_Z_CPM;
?>
