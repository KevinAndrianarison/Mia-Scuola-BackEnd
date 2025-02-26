<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Au extends Model
{
    use HasFactory;
    protected $fillable = ['annee_debut', 'annee_fin', 'etablissement_id', "montant_releve", 'montant_certificatScol' ];

    public function etablissement()
    {
        return $this->belongsTo(Etablissement::class);
    }
    public function niveau()
    {
        return $this->hasMany(Niveau::class);
    }
    public function etudiant()
    {
        return $this->hasMany(Etudiant::class);
    }
    public function commande()
    {
        return $this->hasMany(Commande::class);
    }

    public function mention()
    {
        return $this->hasMany(Mention::class);
    }
    public function ec()
    {
        return $this->hasMany(Ec::class);
    }
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function groupedt()
    {
        return $this->hasMany(Groupedt::class);
    }
}
