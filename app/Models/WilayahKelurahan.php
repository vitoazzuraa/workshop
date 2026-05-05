<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WilayahKelurahan extends Model
{
    protected $table = 'wilayah_kelurahan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['id', 'id_kecamatan', 'nama'];
}
