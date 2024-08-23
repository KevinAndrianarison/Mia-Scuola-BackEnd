<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcour extends Model
{
    use HasFactory;
    protected $fillable = ['abr_parcours', 'nom_parcours', "mention_id"];

    public function mention()
    {
        return $this->belongsTo(Mention::class);
    }
}
