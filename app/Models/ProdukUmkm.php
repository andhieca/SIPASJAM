<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukUmkm extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class);
    }
}
