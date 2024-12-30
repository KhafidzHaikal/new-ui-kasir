<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class simpanan_induk extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function simpanan() {
        return $this->hasOne(Simpanan::class);
    }
}
