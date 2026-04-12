<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $table = 'guest';
    protected $primaryKey = 'idguest';
    protected $fillable = [
        'nama_guest'
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'idguest', 'idguest');
    }
}
