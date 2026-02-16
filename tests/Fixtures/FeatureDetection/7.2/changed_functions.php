#Test
Description: Changed functions in PHP 7.2 - functionality that is no longer possible in 7.2.
Parser: 7.1
Max: 7.1
EveryLine: true

<?php
get_class(null);
?>


#Test
Description: Changed functions in PHP 7.0 - functionality that is possible from 7.2.
Parser: 7.2
Min: 7.2
EveryLine: true

<?php
mail('', '', '', []);
mb_send_mail('', '', '', []);
?>
