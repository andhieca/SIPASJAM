<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'additional_data' => 'array',
        ];
    }

    public function umkms()
    {
        return $this->hasMany(Umkm::class);
    }

    public function kopdes()
    {
        return $this->hasMany(Kopdes::class);
    }
}
