#Test
Description: Enumerations were added in PHP 8.1.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
enum foo { case BAR; case BAZ; }
enum fooBacked: string { case BAR = 'bar'; case BAZ = 'baz'; }
?>