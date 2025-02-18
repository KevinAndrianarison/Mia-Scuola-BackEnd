<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentionacceuil extends Model
{
    use HasFactory;
    protected $fillable = [
        'nomMention',
        'descriptionMention',
        'photo_name',
    ];
}
