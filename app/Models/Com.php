<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Com extends Model
{
    use HasFactory;
    protected $fillable = ['contenu', 'annonce_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }
}
