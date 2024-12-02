<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'montant', 'type', 'description', 'categorie', 'user_id', 'au_id'];

    public function au()
    {
        return $this->belongsTo(Au::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
