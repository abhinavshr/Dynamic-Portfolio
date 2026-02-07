<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExperienceResponsibility extends Model
{
    use HasFactory;

    protected $fillable = [
        'experience_id',
        'responsibility',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }
}
