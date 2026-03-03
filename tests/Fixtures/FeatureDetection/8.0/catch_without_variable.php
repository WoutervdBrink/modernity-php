#Test
Description: Writing catch without a variable is possible as of PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
try {} catch (Exception) {}
try {} catch (A|B) {}
?>