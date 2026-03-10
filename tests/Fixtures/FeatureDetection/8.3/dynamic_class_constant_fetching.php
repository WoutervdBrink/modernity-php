#Test
Description: Class constants can be fetched dynamically as of PHP 8.3.
Parser: 8.3
Min: 8.3

<?php
echo foo::{$bar};
?>