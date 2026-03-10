#Test
Description: Class, interface, trait, and enum constants support type declarations as of PHP 8.3.
Parser: 8.3
Min: 8.3
EveryLine: true

<?php
class foo1 { const string bar = 'bar'; }
interface foo2 { const string bar = 'bar'; }
trait foo3 { const string bar = 'bar'; }
enum foo4 { const string bar = 'bar'; }
?>