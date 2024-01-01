<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRoleEnum;
use App\Helpers\Utilities;
use App\Models\School;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        if (User::count() == 0) {
            User::create([
                'email' => "admin@admin.com",
                'username' => "adminUsername",
                'password' => "123456789",
                'remember_token' => Str::random(10),
                'role' => UserRoleEnum::ADMIN
            ]);

            $user = User::create([
                'email' => "omar@gg.com",
                'username' => "omar School",
                'password' => "123123",
                'remember_token' => Str::random(10),
                'role' => UserRoleEnum::SCHOOL
            ]);
            School::create([
                'avatar' => 'image1.png',
                'school_name' => 'omar School',
                'user_id' => $user->id,
                'code' => Utilities::generateRandomCode()
            ]);
        }
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
