#Test
Description: Readonly properties were added in PHP 8.1.
Parser: 8.1
Min: 8.1

<?php
class foo { readonly string $bar; }
?>