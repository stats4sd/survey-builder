<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('modules')->delete();

        \DB::table('modules')->insert(array (
            0 =>
            array (
                'id' => 1,
                'slug' => 'metadata_start',
                'theme_id' => 5,
            'title' => 'Metadata (Start)',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 2,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:21:30',
                'updated_at' => '2021-08-25 13:24:55',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            1 =>
            array (
                'id' => 2,
                'slug' => 'metadata_end',
                'theme_id' => 5,
            'title' => 'Metadata (end)',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 2,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:25:21',
                'updated_at' => '2021-08-25 13:25:21',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            2 =>
            array (
                'id' => 3,
                'slug' => 'demographics',
                'theme_id' => 1,
                'title' => 'Demographics',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 5,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:27:10',
                'updated_at' => '2021-08-25 13:27:10',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            3 =>
            array (
                'id' => 4,
                'slug' => 'land_use',
                'theme_id' => 2,
                'title' => 'Farm Land Use',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 4,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:27:26',
                'updated_at' => '2021-08-25 13:28:24',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            4 =>
            array (
                'id' => 5,
                'slug' => 'crops',
                'theme_id' => 2,
                'title' => 'Crop Production and Use',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 10,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:27:42',
                'updated_at' => '2021-08-25 13:28:17',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            5 =>
            array (
                'id' => 6,
                'slug' => 'crop_intensification',
                'theme_id' => 2,
                'title' => 'Crop Intensification',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 5,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:28:05',
                'updated_at' => '2021-08-25 13:28:05',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            6 =>
            array (
                'id' => 7,
                'slug' => 'land_management',
                'theme_id' => 2,
                'title' => 'Land Management',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 4,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:28:44',
                'updated_at' => '2021-08-25 13:28:44',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            7 =>
            array (
                'id' => 8,
                'slug' => 'livestock',
                'theme_id' => 2,
                'title' => 'Livestock Production and Use',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 8,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:29:06',
                'updated_at' => '2021-08-25 13:29:06',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            8 =>
            array (
                'id' => 9,
                'slug' => 'livestock_intensification',
                'theme_id' => 2,
                'title' => 'Livestock Intensification',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 2,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:29:30',
                'updated_at' => '2021-08-25 13:29:30',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            9 =>
            array (
                'id' => 10,
                'slug' => 'wildfoods',
                'theme_id' => 3,
                'title' => 'Wildfoods',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 2,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:29:47',
                'updated_at' => '2021-08-25 13:29:47',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            10 =>
            array (
                'id' => 11,
                'slug' => 'food_security',
                'theme_id' => 3,
                'title' => 'Food Security',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 2,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:30:59',
                'updated_at' => '2021-08-25 13:30:59',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            11 =>
            array (
                'id' => 12,
                'slug' => 'hdds',
                'theme_id' => 3,
                'title' => 'Dietary Diversity',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 7,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:31:22',
                'updated_at' => '2021-08-25 13:31:22',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            12 =>
            array (
                'id' => 13,
                'slug' => 'off_farm_income',
                'theme_id' => 6,
                'title' => 'Off Farm Income',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 5,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:31:57',
                'updated_at' => '2021-08-25 13:31:57',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            13 =>
            array (
                'id' => 14,
                'slug' => 'debts_aid',
                'theme_id' => 6,
                'title' => 'Credit, Aid, Debts',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 3,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:32:21',
                'updated_at' => '2021-08-25 13:32:21',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            14 =>
            array (
                'id' => 15,
                'slug' => 'ppi',
                'theme_id' => 6,
                'title' => 'Poverty Probabilty Index',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 3,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:32:40',
                'updated_at' => '2021-08-25 13:32:40',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
            15 =>
            array (
                'id' => 16,
                'slug' => 'fies',
                'theme_id' => 6,
                'title' => 'FIES',
                'logo' => NULL,
                'localisation_needs' => NULL,
                'r_scripts' => NULL,
                'requires' => NULL,
                'requires_before' => NULL,
                'minutes' => 4,
                'core' => 1,
                'live' => 0,
                'created_at' => '2021-08-25 13:32:40',
                'updated_at' => '2021-08-25 13:32:40',
                'parent_id' => 0,
                'lft' => 0,
                'rgt' => 0,
                'depth' => 0,
            ),
        ));


    }
}