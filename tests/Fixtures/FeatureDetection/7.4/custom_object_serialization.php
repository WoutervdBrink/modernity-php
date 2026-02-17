#Test
Description: A new mechanism for custom object serialization has been added in PHP 7.4, with magic method __serialize().
Parser: 7.4
Min: 7.4

<?php
class foo {
    public function __serialize(): array
    {
    }
}
?>


#Test
Description: A new mechanism for custom object serialization has been added in PHP 7.4, with magic method __unserialize().
Parser: 7.4
Min: 7.4

<?php
class bar {
    public function __unserialize(array $data): void
    {
    }
}
?>
