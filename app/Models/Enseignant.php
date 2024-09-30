<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;
    protected $fillable = ['nomComplet_ens', 'telephone_ens', 'date_recrutement_ens', 'grade_ens', 'categorie_ens', 'chefMention_status', 'chefParcours_status',  'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function mention()
    {
        return $this->hasMany(Mention::class);
    }
    public function parcour()
    {
        return $this->hasMany(Parcour::class);
    }
    public function ec()
    {
        return $this->hasMany(Ec::class);
    }
}
