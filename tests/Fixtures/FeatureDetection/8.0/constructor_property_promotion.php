#Test
Description: Constructor property promotion was added in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
class foo { public function __construct(public int $x) {} }
class bar { public function __construct(protected int $x) {} }
class baz { public function __construct(private int $x) {} }
class foo2 { public function __construct(int $y, public int $x) {} }
class bar2 { public function __construct(int $y, protected int $x) {} }
class baz2 { public function __construct(int $y, private int $x) {} }
?>