<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'foto_sekolah' => 'array',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}
