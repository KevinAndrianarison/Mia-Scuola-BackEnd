<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directeur extends Model
{
    use HasFactory;
    protected $fillable = ['nomComplet_dir', 'grade_dir', 'telephone_dir', 'user_id' ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
