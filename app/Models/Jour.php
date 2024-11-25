<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jour extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];
    protected $table = 'jours'; 

    public function edt()
    {
        return $this->hasMany(Edt::class);
    }
}
