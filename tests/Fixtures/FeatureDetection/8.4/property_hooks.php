#Test
Description: Property hooks were added in PHP 8.4.
Parser: 8.4
Min: 8.4
EveryLine: true

<?php
class foo_with_getter { public string $foo { get => $this->bar; }}
class foo_with_setter { public string $foo { set => $value; }}
?>