#Test
Description: Constructor promotion for final properties was added in PHP 8.5.
Parser: 8.5
Min: 8.5

<?php
class foo
{
    public function __construct(public final string $bar)
    {
    }
}
?>