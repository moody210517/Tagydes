<?php

use Tagydes\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin',
            'display_name' => 'Admin',
            'description' => 'System administrator.',
            'removable' => false,
            'order' => '1'
        ]);

        Role::create([
            'name' => 'Reseller',
            'display_name' => 'Reseller',
            'description' => 'Reseller Users.',
            'removable' => false,
            'order' => '2'
        ]);

        Role::create([
            'name' => 'Branch Office',
            'display_name' => 'Branch',
            'description' => 'Default system user.',
            'removable' => false,
            'order' => '3'
        ]);

        Role::create([
            'name' => 'User',
            'display_name' => 'User',
            'description' => 'Default system user.',
            'removable' => false,
            'order' => '4'
        ]);

        Role::create([
            'name' => 'Supervisor',
            'display_name' => 'Supervisor',
            'description' => 'Supervisor system user.',
            'removable' => false,
            'order' => '5'
        ]);


    }
}
