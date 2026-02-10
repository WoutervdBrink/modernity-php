#Test
Description:  Class member access on cloning has been added in PHP 7.0 (methods).
Parser: 7.0
Min: 7.0

<?php
(clone $foo)->bar();
?>


#Test
Description:  Class member access on cloning has been added in PHP 7.0 (properties).
Parser: 7.0
Min: 7.0

<?php
(clone $foo)->bar;
?>