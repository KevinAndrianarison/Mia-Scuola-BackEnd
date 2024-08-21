<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;
    protected $fillable = ['nom_niveau','abr_niveau', 'au_id'];

    public function au()
    {
        return $this->belongsTo(Au::class);
    }
    public function mention()
    {
        return $this->hasMany(Mention::class);
    }
}
