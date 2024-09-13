<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    use HasFactory;
    protected $fillable = ['nom_ue', 'credit_ue', 'semestre_id'];
    public function semestre()
    {
        return $this->belongsTo(Semestre::class);
    }

}
