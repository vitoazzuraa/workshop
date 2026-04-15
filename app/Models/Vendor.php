<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table      = 'vendor';
    protected $primaryKey = 'idvendor';
    protected $fillable   = [
        'user_id',
        'nama_vendor',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function menu()
    {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }
}
