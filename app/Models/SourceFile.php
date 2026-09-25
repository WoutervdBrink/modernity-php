<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table(key: 'sha256', keyType: 'string', incrementing: false)]
final class SourceFile extends Model
{
    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }
}
