<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Document;
use App\Models\Faq;
use App\Models\Menu;
use App\Models\Job;
use App\Models\News;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Settings;
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
        $this->makeDirectories();
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

        //Create Documents
        Document::factory(20)->create();

        //Create CMS
        Page::factory(20)->create();

        //Create Create FAQ
        Faq::factory(20)->create();

         //Create Job
        $sampleJob = Job::factory(20)->create();

        //Create Job Applications
        Application::factory(5)->create([
            'job_id' => $sampleJob[0]->id,
        ]);

        Application::factory(5)->create([
            'job_id' => $sampleJob[1]->id,
        ]);

        Application::factory(10)->create();

        // Set current theme
        Settings::factory(1)->create([
            'name' => 'current_theme',
            'value' => 'theme1'
        ]);
        
        // Create News Letters
        Newsletter::factory(20)->create();
    }


    /**
     * Create directories for storing assets in public directory of the application.
     *
     * @return void
     */
    private function makeDirectories()
    {
        // For User Profile
        if (!is_dir(public_path(config('filepaths.userProfileImagePath.public_path')))) {
            mkdir(public_path(config('filepaths.userProfileImagePath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.userProfileImagePath.internal_path')))) {
            mkdir(base_path(config('filepaths.userProfileImagePath.internal_path')), 0777, true);
        }

        // For Page Images
        if (!is_dir(public_path(config('filepaths.pageImagePath.public_path')))) {
            mkdir(public_path(config('filepaths.pageImagePath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.pageImagePath.internal_path')))) {
            mkdir(base_path(config('filepaths.pageImagePath.internal_path')), 0777, true);
        }

        // For News Images
        if (!is_dir(public_path(config('filepaths.newsImagePath.public_path')))) {
            mkdir(public_path(config('filepaths.newsImagePath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.newsImagePath.internal_path')))) {
            mkdir(base_path(config('filepaths.newsImagePath.internal_path')), 0777, true);
        }

        // For Job Images
        if (!is_dir(public_path(config('filepaths.jobImagePath.public_path')))) {
            mkdir(public_path(config('filepaths.jobImagePath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.jobImagePath.internal_path')))) {
            mkdir(base_path(config('filepaths.jobImagePath.internal_path')), 0777, true);
        }

        // For Documents Images
        if (!is_dir(public_path(config('filepaths.documentsFilePath.internal_path')))) {
            mkdir(public_path(config('filepaths.documentsFilePath.internal_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.documentsFilePath.public_path')))) {
            mkdir(base_path(config('filepaths.documentsFilePath.public_path')), 0777, true);
        }

        // For CK-Editor Images
        if (!is_dir(public_path(config('filepaths.ckeditorImagePath.public_path')))) {
            mkdir(public_path(config('filepaths.ckeditorImagePath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.ckeditorImagePath.internal_path')))) {
            mkdir(base_path(config('filepaths.ckeditorImagePath.internal_path')), 0777, true);
        }

        // For Applications
        if (!is_dir(public_path(config('filepaths.applicationsPath.public_path')))) {
            mkdir(public_path(config('filepaths.applicationsPath.public_path')), 0777, true);
        }

        if (!is_dir(base_path(config('filepaths.applicationsPath.internal_path')))) {
            mkdir(base_path(config('filepaths.applicationsPath.internal_path')), 0777, true);
        }
    }
}
