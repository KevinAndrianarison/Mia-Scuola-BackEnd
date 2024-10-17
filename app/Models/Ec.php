<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ec extends Model
{
    use HasFactory;
    protected $fillable = ['nom_ec', 'volume_et', 'volume_ed', 'volume_tp', 'ue_id', 'au_id', 'enseignant_id'];
    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }
    public function au()
    {
        return $this->belongsTo(Au::class);
    }
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
    public function cour()
    {
        return $this->hasMany(Cour::class);
    }
    public function note()
    {
        return $this->hasMany(Note::class);
    }
}
