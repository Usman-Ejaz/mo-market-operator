<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Menu;
use App\Models\News;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
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
        // Create Roles
        $adminRole = Role::factory()->create(['name' => 'Administrator']);
        $employeeRole = Role::factory()->create(['name' => 'Employee']);
        $roles = Role::factory(1)->create();

        // Grant all permissions to admin
        $permissions = config('permissions');
        foreach( $permissions as $permission ) {
            foreach( $permission['capabilities'] as $capability_name => $capability_display_name){
                Permission::factory()->create([
                    'role_id' => $adminRole->id,
                    'name' => $permission['name'],
                    'capability' => $capability_name
                ]);
            }
        }

        // Create Admin Users
        $adminUsers = User::factory(1)->create([
            'name' => 'Omer Farooq',
            'email' => 'omer@gmail.com',
            'role_id' => $adminRole->id,
            'active' => 1
        ]);

        // Create Employee users
        $employeeUsers = User::factory(2)->create([
            'role_id' => $employeeRole->id,
            'created_by' => $adminRole->id,
            'modified_by' => $adminRole->id
        ]);

        // Create Other users
        $employeeUsers = User::factory(5)->create([
            'role_id' => $roles[0]->id,
            'created_by' => $adminRole->id,
            'modified_by' => $employeeRole->id
        ]);

        // Create Menus
        Menu::factory()->create(['name' => 'Menu header']);
        Menu::factory()->create(['name' => 'Menu footer']);
        Menu::factory(2)->create();

        // Create News
        News::factory(30)->create();

        // Create FAQ
        Faq::factory(30)->create();
    }
}
