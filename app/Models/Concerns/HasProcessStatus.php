<?php

namespace App\Models\Concerns;

use App\Data\ProcessStatusCounts;
use App\Models\Enums\ProcessStatus;
use Illuminate\Database\Query\Builder;
use LogicException;

trait HasProcessStatus
{
    public static function getProcessStatusCounts(?Builder $query = null): ProcessStatusCounts
    {
        return ProcessStatusCounts::from(($query ?? static::query()->toBase())
            ->selectRaw('count(id) as total, '.static::$processStatusColumn)
            ->groupBy(static::$processStatusColumn)
            ->orderBy(static::$processStatusColumn)
            ->get()
            ->pluck('total', static::$processStatusColumn)
            ->map(fn ($total): int => (int) $total));
    }

    public function setProcessPending(): void
    {
        $this->setProcessStatus(ProcessStatus::PENDING);
        $this->save();
    }

    private function setProcessStatus(ProcessStatus $to): void
    {
        if (! $this->{static::$processStatusColumn}->isTransitionAllowed($to)) {
            throw new LogicException('Transitioning from status '.$this->{static::$processStatusColumn}->name.' to '.$to->name.' is not allowed.');
        }

        $this->{static::$processStatusColumn} = $to;

        if ($to === ProcessStatus::COMPLETED) {
            $this->{static::$processCompletedAtColumn} ??= now()->toImmutable();
        } else {
            $this->{static::$processCompletedAtColumn} = null;
        }
    }

    public function setProcessQueued(): void
    {
        $this->setProcessStatus(ProcessStatus::QUEUED);
        $this->save();
    }

    public function setProcessRunning(): void
    {
        $this->setProcessStatus(ProcessStatus::RUNNING);
        $this->save();
    }

    public function setProcessCompleted(): void
    {
        $this->setProcessStatus(ProcessStatus::COMPLETED);
        $this->save();
    }

    public function setProcessFailed(): void
    {
        $this->setProcessStatus(ProcessStatus::FAILED);
        $this->save();
    }
}
