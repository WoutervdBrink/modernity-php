<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class SearchResult extends Model
{
    protected function casts(): array
    {
        return [
            'observed_data' => 'json',
            'discovered_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Search, $this>
     */
    public function search(): BelongsTo
    {
        return $this->belongsTo(Search::class);
    }

    /**
     * @return BelongsTo<Repository, $this>
     */
    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function isRejected(): Attribute
    {
        return Attribute::get(fn (): bool => $this->rejection_reason !== null);
    }
}
