#Test
Description: As of PHP 7.2, a warning will be emitted when attempting to count non-countable types.
Parser: 7.1
Max: 7.1
EveryLine: true

<?php
count(null); // NULL is not countable
count(1); // integers are not countable
count(1.2); // floats are not countable
count('abc'); // strings are not countable
sizeof(null); // NULL is not countable
sizeof(1); // integers are not countable
sizeof(1.2); // floats are not countable
sizeof('abc'); // strings are not countable
?>