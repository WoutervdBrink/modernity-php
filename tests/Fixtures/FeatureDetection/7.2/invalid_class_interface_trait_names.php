#Test
Description: As of PHP 7.2, object cannot be used to name classes.
Parser: 7.2
Max: 7.1

<?php
class object {}
?>


#Test
Description: As of PHP 7.2, object cannot be used to name interfaces.
Parser: 7.2
Max: 7.1

<?php
interface object {}
?>


#Test
Description: As of PHP 7.2, object cannot be used to name traits.
Parser: 7.2
Max: 7.1

<?php
trait object {}
?>