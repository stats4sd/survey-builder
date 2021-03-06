<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);

        if (config('app.env') === 'local') {
            $this->call(TestSeeder::class);
        }

        $this->call(ThemeSeeder::class);
        $this->call(ModulesTableSeeder::class);
    }
}
