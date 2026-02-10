#Test
Description: Changed functions in PHP 7.0 - functionality that is no longer possible in 7.0.
Parser: 7.0
Max: 5.6
PerLine: true

<?php
mktime(0, 0, 0, 0, 0, 0, true);
gmmktime(0, 0, 0, 0, 0, 0, true);
setlocale('string');
?>


#Test
Description: Changed functions in PHP 7.0 - functionality that is possible from 7.0.
Parser: 7.0
Min: 7.0
PerLine: true

<?php
dirname('', 1);
?>
