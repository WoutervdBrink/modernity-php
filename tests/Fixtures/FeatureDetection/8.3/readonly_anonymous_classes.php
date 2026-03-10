#Test
Description: Support for readonly anonymous classes was added in PHP 8.3.
Parser: 8.3
Min: 8.3

<?php
new readonly class {};
?>