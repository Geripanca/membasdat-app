<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Materi;
use App\Models\Application;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(5)->create();
        Application::create([
            'name_app' => 'Membasdat',
            'description_app' => 'Membasdat adalah platform edukasi untuk mempelajari ERD.'
        ]);

        User::create([
            'name' => 'Bubble Smith',
            'email' => 'bubble@gmail.com',
            'username' => 'admin',
            'image' => 'profil-images/5.jpeg',
            'is_admin' => 1,
            'gender' => 'Laki-Laki',
            'password' => bcrypt('@Admin123')
        ]);




    }
}
