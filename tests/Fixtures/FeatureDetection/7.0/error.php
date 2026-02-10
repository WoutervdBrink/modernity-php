#Test
Description: The Error class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try {

} catch (Error $t) {}
?>


#Test
Description: The Error class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
class Foo extends Error {}
?>


#Test
Description: The ArithmeticError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (ArithmeticError $t) {}
?>

#Test
Description: The DivisionByZeroError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (DivisionByZeroError $t) {}
?>

#Test
Description: The AssertionError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (AssertionError $t) {}
?>

#Test
Description: The CompileError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (CompileError $t) {}
?>

#Test
Description: The ParseError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (ParseError $t) {}
?>

#Test
Description: The TypeError class was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
try { } catch (TypeError $t) {}
?>