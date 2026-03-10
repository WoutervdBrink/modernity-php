#Test
Description: Never and readonly can no longer be used as a class, interface or trait name.
Parser: 8.0
Max: 8.0
EveryLine: true

<?php
class never {}
interface never {}
trait never {}
class readonly {}
interface readonly {}
trait readonly {}
?>