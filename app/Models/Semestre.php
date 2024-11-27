<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
    use HasFactory;
    protected $fillable = ['nom_semestre', 'parcour_id'];

    public function parcour()
    {
        return $this->belongsTo(Parcour::class);
    }
    public function ue()
    {
        return $this->hasMany(Ue::class);
    }
    public function etudiant()
    {
        return $this->belongsToMany(Etudiant::class, 'etudiant_semestre', 'semestre_id', 'etudiant_id');
    }
    public function groupedt()
    {
        return $this->hasMany(Groupedt::class);
    }
}
