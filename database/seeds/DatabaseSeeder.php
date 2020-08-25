<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            CdrGroupTableSeeder::class,
            SettingsTableSeeder::class,
        ]);
    }
}
