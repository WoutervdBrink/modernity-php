#Test
Description: The Deprecated attribute was added in PHP 8.4.
Parser: 8.4
Min: 8.4

<?php
class foo extends bar { #[Deprecated] function foo() {} }
?>