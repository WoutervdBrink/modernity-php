<?php

namespace App\Models\Enums;

enum SearchStatus: string
{
    case PENDING = 'pending';
    case RUNNING = 'running';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
