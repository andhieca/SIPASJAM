<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kopdes extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'additional_data' => 'array',
            'status_aktif' => 'boolean',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
