#Test
Description: Match can no longer be used as a class, interface or trait name.
Parser: 7.4
Max: 7.4
EveryLine: true

<?php
class match {}
interface match {}
trait match {}
?>