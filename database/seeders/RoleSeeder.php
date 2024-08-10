<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => 'Admin', 'created_at' => Carbon::now()],
            ['name' => 'SDM', 'created_at' => Carbon::now()],
            ['name' => 'Staff', 'created_at' => Carbon::now()],
        ];

        Role::insert($data);
    }
}
