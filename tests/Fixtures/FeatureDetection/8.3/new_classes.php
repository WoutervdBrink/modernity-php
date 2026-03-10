#Test
Description: New classes in PHP 8.3
Parser: 8.3
Min: 8.3
EveryLine: true

<?php
echo DateError::class;
echo DateObjectError::class;
echo DateRangeError::class;
echo DateException::class;
echo DateInvalidOperationException::class;
echo DateInvalidTimeZoneException::class;
echo DateMalformedIntervalStringException::class;
echo DateMalformedPeriodStringException::class;
echo DateMalformedStringException::class;
echo SQLite3Exception::class;
?>