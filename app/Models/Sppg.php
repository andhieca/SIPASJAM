<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sppg extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'additional_data' => 'array',
            'foto_sppg' => 'array',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
