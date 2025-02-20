<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Congepermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'dateDebut',
        'dateFin',
        'fichier_nom',
        'type',
        'category',
        'status',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
