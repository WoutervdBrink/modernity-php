#Test
Description: Removed constants in PHP 8.4.
Parser: 8.4
Max: 8.3
EveryLine: true

<?php
echo MYSQLI_SET_CHARSET_DIR;
echo MYSQLI_STMT_ATTR_PREFETCH_ROWS;
echo MYSQLI_CURSOR_TYPE_FOR_UPDATE;
echo MYSQLI_CURSOR_TYPE_SCROLLABLE;
echo MYSQLI_TYPE_INTERVAL;
?>
