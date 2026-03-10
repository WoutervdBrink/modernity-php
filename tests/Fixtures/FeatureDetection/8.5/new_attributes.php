#Test
Description: New attributes added in PHP 8.5.
Parser: 8.5
Min: 8.5
EveryLine: true

<?php
#[NoDiscard] function foo() {}
class foo { #[DelayedTargetValidation] public const bar = 5; }
?>