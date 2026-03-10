#Test
Description: Assymetric property visibility was added in PHP 8.5.
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
class foo { public protected(set) static string $bar; }
class bar { public private(set) static string $bar; }
class baz { protected private(set) static string $bar; }
class foo_with_public_ommitted { protected(set) static string $bar; }
class bar_with_public_ommitted { private(set) static string $bar; }
?>