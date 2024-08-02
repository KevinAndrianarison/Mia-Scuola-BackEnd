<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mention extends Model
{
    use HasFactory;
    protected $fillable = ['nom_mention', 'niveau_id', "abr_mention"];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

}
