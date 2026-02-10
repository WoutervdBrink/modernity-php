#Test
Description: The null coalescing operator was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
echo $_GET['user'] ?? 'nobody';
?>
