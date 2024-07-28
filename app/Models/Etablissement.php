<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etablissement extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom_etab', 'slogan_etab', 'descri_etab', 'abr_etab', 'logo_etab', 'dateCreation_etab'
    ];}
