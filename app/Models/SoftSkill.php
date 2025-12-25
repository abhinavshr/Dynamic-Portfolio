<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftSkill extends Model
{
    use HasFactory;

    protected $table = 'soft_skills';

     protected $fillable = [
        'name',
        'description',
        'level',
    ];
}
