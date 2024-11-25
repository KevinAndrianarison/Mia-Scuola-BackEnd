<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heure extends Model
{
    use HasFactory;
    protected $fillable = ['valeur'];

    public function edt()
    {
        return $this->hasMany(Edt::class);
    }
}
