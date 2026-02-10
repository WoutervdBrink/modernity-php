#Test
Description: The Throwable interface was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try {

} catch (Throwable $t) {}
?>

#Test
Description: The Throwable interface was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
class Foo implements Throwable {}
?>
