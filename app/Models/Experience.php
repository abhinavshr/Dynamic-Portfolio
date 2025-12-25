<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $table = 'experiences';

     protected $fillable = [
        'company_name',
        'role',
        'start_date',
        'end_date',
        'description',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];
}
