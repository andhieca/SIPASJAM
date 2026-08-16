<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'foto_produk' => 'array',
        ];
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function produks()
    {
        return $this->hasMany(ProdukUmkm::class);
    }
}
