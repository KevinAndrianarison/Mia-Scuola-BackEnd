<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;


    protected $fillable = ['titre', 'description', 'fichier_nom', 'user_id', 'categori_id'];
    protected $appends = ['liked_by_user'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function categori()
    {
        return $this->belongsTo(Categori::class);
    }
    public function com()
    {
        return $this->hasMany(Com::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function getLikedByUserAttribute()
    {
        $userId = request()->query('user_id');
        return $this->likes()->where('user_id', $userId)->exists();
    }
}
