#Test
Description: Underscores between digits are allowed since PHP 7.4
Parser: 7.4
Min: 7.4
EveryLine: true

<?php
echo 6.674_083e-11; // float
echo 299_792_458;   // decimal
echo 0xCAFE_F00D;   // hexadecimal
echo 0b0101_1111;   // binary
?>