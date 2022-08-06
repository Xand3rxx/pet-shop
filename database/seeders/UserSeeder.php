<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default administrator user account
        User::create([
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'is_admin'          => User::ROLE['Admin'],
            'email'             => 'admin@buckhill.co.uk',
            'email_verified_at' => now(),
            'password'          => bcrypt('admin'),
            'phone_number'      => fake()->unique()->phoneNumber(),
            'address'           => fake()->unique()->address(),
            'is_marketing'      => User::MARKETER_ROLE['Marketer'],
        ]);

        // Default user account
        User::create([
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'is_admin'          => User::ROLE['User'],
            'email'             => 'john.doe@gmail.com',
            'email_verified_at' => now(),
            'password'          => bcrypt('userpassword'),
            'phone_number'      => fake()->unique()->phoneNumber(),
            'address'           => fake()->unique()->address(),
            'is_marketing'      => User::MARKETER_ROLE['User'],
        ]);

        // Generate 3 other users
        User::factory(3)->create();
    }
}
