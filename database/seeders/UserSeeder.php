<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //create administrator user
        $role = (new Role())->where('slug', '=', 'ADMINISTRATOR')->first();

        $user = User::create([
            'username' => 'admin',
            'email' => 'admin@cognotiv.sg',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => $role->id,
            'created_by' => 'system'
        ]);

        $user->contact()->create([
            'nick_name' => 'Satrya',
            'full_name' => 'Satrya Wiguna',
            'created_by' => 'system'
        ]);

        //create randomly user
        Contact::factory()->count(9)->create();
    }
}
