<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;
    protected $fillable = ['nomComplet_etud', 'date_naissance_etud', 'lieux_naissance_etud', 'adresse_etud', 'nationalite_etud', 'serieBAC_etud', 'telephone_etud', 'anneeBAC_etud', 'etabOrigin_etud', 'matricule_etud', 'nom_mere_etud', 'nom_pere_etud', 'sexe_etud', 'CIN_etud', 'nom_tuteur', 'user_id', 'validiter_inscri', "photoBordereaux_name"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function semestre()
    {
        return $this->belongsToMany(Semestre::class, 'etudiant_semestre', 'etudiant_id', 'semestre_id');
    }
    public function note()
    {
        return $this->hasMany(Note::class);
    }
    public function ec()
    {
        return $this->belongsToMany(Ec::class, 'ec_etudiant')->withPivot('noteEc')->withTimestamps();
    }
}
