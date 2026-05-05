<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps = false;

    protected $fillable = [
        'guest_id', 'nama', 'total', 'metode_bayar', 'status_bayar',
        'midtrans_order_id', 'midtrans_snap_token',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}
