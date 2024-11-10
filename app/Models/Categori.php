<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categori extends Model
{
    use HasFactory;
    protected $fillable = ['titre'];
    public function annonce()
    {
        return $this->hasMany(Annonce::class);
    }
}
