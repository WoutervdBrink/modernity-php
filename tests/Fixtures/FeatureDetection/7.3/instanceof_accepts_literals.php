#Test
Description: As of PHP 7.3, instanceof accepts literals as the first operand.
Parser: 7.3
Min: 7.3
EveryLine: true

<?php
1 instanceof foo;
'foo' instanceof foo;
3.14 instanceof foo;
?>