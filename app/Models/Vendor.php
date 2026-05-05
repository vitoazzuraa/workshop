<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps = false;

    protected $fillable = ['nama_vendor'];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'vendor_id', 'idvendor');
    }
}
