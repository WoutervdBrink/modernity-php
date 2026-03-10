#Test
Description: The singleton null, false and true types were added in PHP 8.2.
Parser: 8.2
Min: 8.2
EveryLine: true

<?php
function returns_null(): null { return null; }
function returns_true(): true { return true; }
function returns_false(): false { return false; }
class nullClass { function foo(): null { return null; }}
class trueClass { function foo(): true { return true; }}
class falseClass { function foo(): false { return false; }}
?>