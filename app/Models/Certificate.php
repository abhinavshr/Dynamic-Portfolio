<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'title',
        'issuer',
        'issue_date',
        'certificate_photo',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];
}
