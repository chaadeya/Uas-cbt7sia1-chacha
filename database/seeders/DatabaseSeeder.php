<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sellaaprilia190@gmail.com'],
            [
                'name' => 'Sella Aprilia',
                'username' => 'sella',
                'is_staff' => true,
                'password' => Hash::make('130405'),
            ]
        );

        $this->call([
            QuestionSeeder::class,
        ]);
    }
}