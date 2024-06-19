<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            User::create([
                'name' => $faker->name,
                'username' => $faker->unique()->userName,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => $faker->dateTime(),
                'password' => Hash::make('password'), // Puedes cambiar la contraseña por defecto
                'admin' => $faker->boolean(20), // 20% de probabilidad de que el usuario sea admin
                'remember_token' => Str::random(10),
                'profile_photo_path' => $faker->optional()->imageUrl(640, 480, 'people')
            ]);
        }
    }
}
