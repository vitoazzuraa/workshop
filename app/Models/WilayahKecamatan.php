<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKecamatan extends Model
{
    protected $table = 'wilayah_kecamatan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'id_kota', 'nama'];

    public function kelurahan()
    {
        return $this->hasMany(WilayahKelurahan::class, 'id_kecamatan', 'id');
    }
}
