<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\Page;
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

        // News
        News::factory(30)->create();

        //CMS
        Page::factory(20)->create();
    }
}
