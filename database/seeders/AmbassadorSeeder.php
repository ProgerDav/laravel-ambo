<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class AmbassadorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => "Admin",
            'last_name' => "Admin",
            'password' => Hash::make('password'),
            'email' => 'admin@gmail.com',
            'is_admin' => 1
        ]);

        User::factory(10)->create(['is_admin' => 0]);
    }
}
