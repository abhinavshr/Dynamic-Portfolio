<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'name',
        'level',
        'category_id',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
