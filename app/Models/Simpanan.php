<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;
    protected $table = 'simpanan';
    protected $primaryKey = 'id_simpanan';
    protected $guarded = [];

    public function simpanan_induks() {
        return $this->hasOne(simpanan_induk::class);
    }
}
