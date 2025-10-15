#Test
Description: Argument unpacking via ... was introduced in PHP 5.6
Parser: 5.6
Min: 5.6

<?php
$bar = [1, 2, 3];
foo(...$bar);
?>