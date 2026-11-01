<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    protected $fillable = [
        'batch_id',
        'file_name',
        'row_number',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'row_number' => 'integer',
        ];
    }
}
