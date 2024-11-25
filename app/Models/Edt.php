<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edt extends Model
{
    use HasFactory;
    protected $fillable = ['jour_id', 'heure_id', 'enseignant_id', 'salle_id', 'ec_id'];
    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }
    public function heure()
    {
        return $this->belongsTo(Heure::class);
    }
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }
    public function ec()
    {
        return $this->belongsTo(Ec::class);
    }
}
