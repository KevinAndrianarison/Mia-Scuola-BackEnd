<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcour extends Model
{
    use HasFactory;
    protected $fillable = ['abr_parcours', 'nom_parcours', "mention_id", "niveau_id"];

    public function mention()
    {
        return $this->belongsTo(Mention::class);
    }
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    public function semestre()
    {
        return $this->hasMany(Semestre::class);
    }
}
