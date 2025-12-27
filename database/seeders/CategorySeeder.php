<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Web Development'],
            ['name' => 'Mobile App Development'],
            ['name' => 'Frontend Development'],
            ['name' => 'Backend Development'],
            ['name' => 'Full Stack Development'],
            ['name' => 'Software Development'],
            ['name' => 'UI / UX Design'],
            ['name' => 'API Development'],
            ['name' => 'DevOps'],
            ['name' => 'Cloud Computing'],
            ['name' => 'AI / Machine Learning'],
            ['name' => 'Data Science'],
            ['name' => 'Blockchain Development'],
            ['name' => 'Game Development'],
            ['name' => 'QA & Testing'],
            ['name' => 'Maintenance & Support'],
            ['name' => 'Other'],
        ]);
    }
}
