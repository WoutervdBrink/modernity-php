<?php

namespace App\Models;

use App\Models\Enums\SearchStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Search extends Model
{
    protected function casts(): array
    {
        return [
            'parameters' => 'json',
            'checkpoint' => 'json',
            'status' => SearchStatus::class,
            'started_at' => 'timestamp',
            'finished_at' => 'timestamp',
        ];
    }

    /**
     * @return HasMany<SearchResult, $this>
     */
    public function results(): HasMany
    {
        return $this->hasMany(SearchResult::class);
    }
}
