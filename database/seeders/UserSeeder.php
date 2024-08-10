<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'role_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 2,
                'name' => 'SDM 1',
                'email' => 'sdm1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 2,
                'name' => 'SDM 2',
                'email' => 'sdm2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 3,
                'name' => 'Staff 1',
                'email' => 'staff1@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 3,
                'name' => 'Staff 2',
                'email' => 'staff2@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 3,
                'name' => 'Staff 3',
                'email' => 'staff3@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
            [
                'role_id' => 3,
                'name' => 'Staff 4',
                'email' => 'staff4@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('thispassword'),
                'created_at' => Carbon::now()
            ],
        ];

        User::insert($data);
    }
}
