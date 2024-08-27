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
}
