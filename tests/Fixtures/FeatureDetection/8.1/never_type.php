#Test
Description: The never return type was added in PHP 8.1
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
function foo(): never {}
class foo { function bar(): never {}}
?>