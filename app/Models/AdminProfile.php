<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'professional_title',
        'tagline',
        'about_me',
        'years_of_experience',
        'projects_completed',
        'happy_clients',
        'technologies_used',
        'github_url',
        'linkedin_url',
        'cv_url',
        'twitter_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
