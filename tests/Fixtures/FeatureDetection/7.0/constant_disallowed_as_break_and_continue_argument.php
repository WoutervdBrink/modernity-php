#Test
Description: As of PHP 7.0, break statements no longer allow their argument to be a constant.
Parser: 7.0
Max: 5.6

<?php
for (;;) break foo;
?>


#Test
Description: As of PHP 7.0, continue statements no longer allow their argument to be a constant.
Parser: 7.0
Max: 5.6

<?php
for (;;) continue foo;
?>
