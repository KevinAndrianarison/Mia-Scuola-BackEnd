<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cursu extends Model
{
    use HasFactory;
    public function etudiant()
    {
        return $this->hasMany(Etudiant::class);
    }
}
