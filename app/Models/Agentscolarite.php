<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentscolarite extends Model
{
    use HasFactory;
    protected $fillable = ['nomComplet_scol', 'telephone_scol', 'date_recrutement_scol', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
