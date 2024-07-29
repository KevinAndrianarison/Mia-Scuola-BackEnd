<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Au extends Model
{
    use HasFactory;
    protected $fillable = ['annee_debut', 'annee_fin', 'etablissement_id'];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function niveau()
    {
        return $this->hasMany(Niveau::class);
    }
}
