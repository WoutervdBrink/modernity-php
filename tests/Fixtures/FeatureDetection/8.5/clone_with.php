#Test
Description: Updating properties during object cloning was added in PHP 8.5.
Parser: 8.5
Min: 8.5

<?php
clone($this, ['foo' => 'bar']);
?>