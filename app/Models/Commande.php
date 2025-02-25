<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = ['categorie', 'status', 'date', 'etudiant_id'];
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }
}
