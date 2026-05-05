<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKota extends Model
{
    protected $table = 'wilayah_kota';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'id_provinsi', 'nama'];

    public function kecamatan()
    {
        return $this->hasMany(WilayahKecamatan::class, 'id_kota', 'id');
    }
}
