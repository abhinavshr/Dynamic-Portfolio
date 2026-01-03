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
        'company_location',
        'role',
        'start_date',
        'end_date',
        'is_current',
        'description'
    ];


    protected $dates = [
        'start_date',
        'end_date',
    ];
}
