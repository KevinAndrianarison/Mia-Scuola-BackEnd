<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acceuilimage extends Model
{
    use HasFactory;
    protected $fillable = [
        'photoNameOne',
        'photoNameTwo',
        'photoNameThree',
    ];
}
