#Test
Description: As of PHP 8.1, it is possible to use new ClassName() expressions as the default value of a parameter, static variable, global constant, and attribute argument.
Parser: 8.1
Min: 8.1
EveryLine: true

<?php
function foo($bar = new baz()) {} // default function parameter value
class foo { function bar($baz = new foo()) {} } // default method parameter value
class bar { static $baz = new foo(); } // static class variable
function bar() { static $bar = new baz(); } // static variable
const FOO = new bar(); // constant
define('BAR', new bar()); // constant
#[Foo(new Bar())] function baz() {} // attribute argument
#[Foo(bar: new Baz())] function bah() {} // attribute named argument
?>