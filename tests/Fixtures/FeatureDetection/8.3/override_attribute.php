#Test
Description: The Override attribute was added in PHP 8.3.
Parser: 8.3
Min: 8.3

<?php
class foo extends bar { #[Override] function foo() {} }
?>