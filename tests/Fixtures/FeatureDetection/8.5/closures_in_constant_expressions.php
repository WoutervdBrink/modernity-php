#Test
Description: As of PHP 8.5, it is possible to use closures (first-class callables) in constant expressions.
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
function foo($bar = baz(...)) {} // default function parameter value
class foo { function bar($baz = foo(...)) {} } // default method parameter value
class bar { static $baz = foo(...); } // static class variable
function bar() { static $bar = baz(...); } // static variable
const FOO = bar(...); // constant
define('BAR', bar(...)); // constant
#[Foo(bar(...))] function baz() {} // attribute argument
#[Foo(bar: baz(...))] function bah() {} // attribute named argument
?>