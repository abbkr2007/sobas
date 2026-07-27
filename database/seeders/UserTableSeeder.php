<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'plain_password' => 'password',
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'admin',
                'mat_id' => 'MAT2500001',
            ],
            [
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'plain_password' => 'password',
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'demo_admin',
                'mat_id' => 'MAT2500002',
            ],
            [
                'first_name' => 'John',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'plain_password' => 'password',
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'user',
                'mat_id' => 'MAT2500003',
            ]
        ];
        foreach ($users as $key => $value) {
            $user = User::create($value);
            $user->assignRole($value['user_type']);
        }
    }
}
