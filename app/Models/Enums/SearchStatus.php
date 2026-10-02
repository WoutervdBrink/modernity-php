<?php

namespace App\Models\Enums;

enum SearchStatus: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    private const array ALLOWED_TRANSITIONS = [
        self::PENDING->value => [self::RUNNING],
        self::RUNNING->value => [self::COMPLETED, self::FAILED],
        self::COMPLETED->value => [],
        self::FAILED->value => [],
    ];

    public function isTransitionAllowed(SearchStatus $to): bool
    {
        return in_array($to, self::ALLOWED_TRANSITIONS[$this->value], true);
    }
}
