<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    use HasFactory;
    protected $fillable = ['nom_mention', 'niveau_id', "abr_mention", "enseignant_id"];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function parcour()
    {
        return $this->hasMany(Parcour::class);
    }

}
