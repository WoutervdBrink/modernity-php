#Test
Description: Strict typing was introduced in PHP 7.0.
Parser: 7.0
Min: 7.0

<?php
declare(strict_types=1);
?>


#Test
Description: Strict typing was introduced in PHP 7.0 (counter-test: should not trigger for ticks).
Parser: 7.0
Min: none

<?php
declare(ticks=1);
?>


#Test
Description: Strict typing was introduced in PHP 7.0 (counter-test: should not trigger for encoding).
Parser: 7.0
Min: none

<?php
declare(encoding='UTF-8');
?>
