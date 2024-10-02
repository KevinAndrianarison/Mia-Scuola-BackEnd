<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_cours',
        'description_cours',
        'categorie_cours',
        'cours_name',
        'ec_id'
    ];

    public function ec()
    {
        return $this->belongsTo(Ec::class);
    }
}
