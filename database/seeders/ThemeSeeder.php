<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Theme::create([
            'title' => 'Decision Making',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Farm',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Food',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Gender',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Metadata',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Poverty Dynamics',
            'description' => 'Lorem Ipsum ... ',
        ]);
        Theme::create([
            'title' => 'Trees',
            'description' => 'Lorem Ipsum ... ',
        ]);
    }
}
