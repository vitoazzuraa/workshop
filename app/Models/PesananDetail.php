<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $table = 'pesanan_detail';
    protected $primaryKey = 'idpesanan_detail';

    protected $fillable = [
        'idpesanan',
        'idmenu',
        'jumlah',
        'harga',
        'subtotal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }
}
