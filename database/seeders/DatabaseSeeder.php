<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionTableSeeder;
use Database\Seeders\CreateMemberUserSeeder;
use Database\Seeders\CreateSuperAdminUserSeeder;
use Database\Seeders\CreateAdministratorUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionTableSeeder::class,
            CreateAdministratorUserSeeder::class,
            CreateSuperAdminUserSeeder::class,
            CreateMemberUserSeeder::class,
        ]);
    }
}
