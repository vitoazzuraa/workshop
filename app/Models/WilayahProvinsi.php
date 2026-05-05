<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahProvinsi extends Model
{
    protected $table = 'wilayah_provinsi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'nama'];

    public function kota()
    {
        return $this->hasMany(WilayahKota::class, 'id_provinsi', 'id');
    }
}
