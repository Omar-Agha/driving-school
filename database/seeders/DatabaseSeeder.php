<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\UserRoleEnum;
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

        if(User::count()==0){
            User::create([
                'email'=>"admin@admin.com",
                'username'=>"adminUsername",
                'password'=>"123456789",
                'remember_token'=>Str::random(10),
                'role'=>UserRoleEnum::ADMIN
            ]);
        }
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
