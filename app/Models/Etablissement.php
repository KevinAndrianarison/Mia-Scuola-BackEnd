<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_etab', 'slogan_etab', 'descri_etab', 'abr_etab', 'logo_name', 'dateCreation_etab', 'ville_etab', 'email_etab', 'pays_etab', 'codePostal_etab'
    ];
    public function au()
    {
        return $this->hasMany(Au::class);
    }
}
