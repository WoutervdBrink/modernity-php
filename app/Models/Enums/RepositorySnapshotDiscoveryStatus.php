<?php

namespace App\Models\Enums;

enum RepositorySnapshotDiscoveryStatus: string
{
    case PENDING = 'pending';
    case QUEUED = 'queued';
    case RUNNING = 'running';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    private const array ALLOWED_TRANSITIONS = [
        self::PENDING->value => [self::QUEUED],
        self::QUEUED->value => [self::RUNNING],
        self::RUNNING->value => [self::COMPLETED, self::FAILED],
        self::COMPLETED->value => [self::QUEUED],
        self::FAILED->value => [self::QUEUED],
    ];

    public function isActive(): bool
    {
        return match ($this) {
            self::QUEUED, self::RUNNING => true,
            default => false,
        };
    }

    public function isTransitionAllowed(self $to): bool
    {
        return in_array($to, self::ALLOWED_TRANSITIONS[$this->value], true);
    }
}
