<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'idpesanan';
    protected $fillable = [
        'id',
        'idguest',
        'total',
        'status_bayar',
        'metode_bayar',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_token'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'idguest', 'idguest');
    }

    public function penyedia()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function detailPesanan()
    {
    return $this->hasMany(PesananDetail::class, 'idpesanan', 'idpesanan');
    }
}

