#Test
Description: Union types were added in PHP 8.0
Parser: 8.0
Min: 8.0
EveryLine: true

<?php
class with_or_property { private X|Y $xy; }
function requires_or_union(X|Y $xy) {}
function returns_or_union(): X|Y {}
?>