<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity',
        'priority',
        'config',
        'is_stackable',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
        ];
    }
}
