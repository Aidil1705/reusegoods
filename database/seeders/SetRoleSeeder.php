<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SetRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRoleId = DB::table('roles')->where('role_name', 'admin')->value('id');
        $buyerRoleId = DB::table('roles')->where('role_name', 'pembeli')->value('id');

        $adminId = DB::table('users')->where('email', 'annisa@gmail.com')->value('id');
        $aidilId = DB::table('users')->where('email', 'aidil@gmail.com')->value('id');

        DB::table('set_roles')->insert([
            ['user_id' => $adminId, 'role_id' => $adminRoleId],
            ['user_id' => $aidilId, 'role_id' => $buyerRoleId],
        ]);
    }
}
