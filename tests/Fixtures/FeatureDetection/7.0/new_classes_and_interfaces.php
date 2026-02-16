#Test
Description: New classes and interfaces in PHP 7.0.
Parser: 7.0
Min: 7.0
EveryLine: true

<?php
IntlChar::BLOCK_CODE_AEGEAN_NUMBERS;
IntlChar::isalnum('a');
ReflectionGenerator::class;
ReflectionType::class;
SessionUpdateTimestampHandlerInterface::class;
class foo1 implements Throwable {}
class foo2 extends Error {}
class foo3 extends TypeError {}
class foo4 extends ParseError {}
class foo5 extends AssertionError {}
class foo6 extends ArithmeticError {}
class foo7 extends DivisionByZeroError {}
?>
