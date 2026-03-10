#Test
Description: Casts in constant expressions were added in PHP 8.5.
Parser: 8.5
Min: 8.5

<?php
const T1 = (int) 0.3;
?>