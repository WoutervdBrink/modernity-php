#Test
Description: Assymetric property visibility was added in PHP 8.4.
Parser: 8.4
Min: 8.4
EveryLine: true

<?php
class foo { public protected(set) string $bar; }
class bar { public private(set) string $bar; }
class baz { protected private(set) string $bar; }
class foo_with_public_ommitted { protected(set) string $bar; }
class bar_with_public_ommitted { private(set) string $bar; }
?>