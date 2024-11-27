<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupedt extends Model
{
    use HasFactory;
    protected $fillable = ['au_id', 'parcour_id', 'semestre_id'];

    public function au()
    {
        return $this->belongsTo(Au::class);
    }
    public function parcour()
    {
        return $this->belongsTo(Parcour::class);
    }
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }
    public function edt()
    {
        return $this->hasMany(Edt::class);
    }
}
