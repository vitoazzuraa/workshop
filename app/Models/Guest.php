<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['customer_code', 'nama', 'no_hp'];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'guest_id');
    }
}
