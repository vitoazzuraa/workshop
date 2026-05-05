<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id_barang';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['nama', 'harga'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id_barang = '00000000';
        });
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'id_barang', 'id_barang');
    }
}
