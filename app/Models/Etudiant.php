<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;
    protected $fillable = ['nomComplet_etud', 'date_naissance_etud', 'adresse_etud', 'telephone_etud', 'matricule_etud', 'nom_mere_etud', 'nom_pere_etud','sexe_etud', 'CIN_etud', 'nom_tuteur', 'user_id', 'validiter_inscri'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function semestre()
    {
        return $this->belongsToMany(Semestre::class,'etudiant_semestre', 'etudiant_id', 'semestre_id');
    }
}
