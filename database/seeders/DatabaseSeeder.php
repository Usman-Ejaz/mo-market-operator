<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\Application;
use App\Models\ChatBotKnowledgeBase;
use App\Models\ContactPageQuery;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Menu;
use App\Models\Job;
use App\Models\Post;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SearchStatistic;
use App\Models\Settings;
use App\Models\StaticBlock;
use App\Models\Subscriber;
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

        // Create Posts
        Post::factory(30)->create();

        DocumentCategory::factory(10)->create();

        //Create Documents
        Document::factory(20)->create();

        //Create CMS
        Page::factory(20)->create();

        FaqCategory::factory(10)->create();

        //Create FAQ
        Faq::factory(20)->create();

         //Create Job
        Job::factory(20)->create();

        //Create Job Applications
        Application::factory(15)->create();

        // Set current theme
        Settings::factory(1)->create([
            'name' => 'current_theme',
            'value' => 'theme1'
        ]);
        
        // Create News Letters
        Newsletter::factory(20)->create();

        // Create Subscribers
        Subscriber::factory(10)->create();

        // Create Contact Page Queries
        ContactPageQuery::factory(20)->create();

        // ApiKey
        ApiKey::factory(1)->create(['value'=> 'vxPwTIcOAwUMU1rREvR1h8UPaHGnZtVZGuH7jzWRWaowXyW33tCxiRZfKM4PuXvC6RXWL7xrqTuXVQDCjzRKickhVE0EqP4maCn8vzt8JYQ9hoNuZoTxDVNBLLdP1r6MMMvxKbYknSa5bcD0rHbCU2oCA3419Y9LcfisDQugd8vmp6yUGkw6NEu9V9AsnxThZJNtn1vq']);

        // Create Search Statistics
        SearchStatistic::factory(20)->create();

        // Create Knowledge Base
        ChatBotKnowledgeBase::factory(20)->create();

        StaticBlock::factory(2)->create();
    }


    /**
     * Create directories for storing assets in public directory of the application.
     *
     * @return void
     */
    private function makeDirectories()
    {

        $basePath = config('filesystems.disks.app.root');

        // For User Profile
        if (!is_dir($basePath . '/' . User::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . User::STORAGE_DIRECTORY, 0777, true);
        }

        // For Page Images
        if (!is_dir($basePath . '/' . Page::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . Page::STORAGE_DIRECTORY, 0777, true);
        }

        // For Posts Images
        if (!is_dir($basePath . '/' . Post::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . Post::STORAGE_DIRECTORY, 0777, true);
        }

        // For Job Images
        if (!is_dir($basePath . '/' . Job::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . Job::STORAGE_DIRECTORY, 0777, true);
        }

        // For Documents Images
        if (!is_dir($basePath . '/' . Document::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . Document::STORAGE_DIRECTORY, 0777, true);
        }

        // For CK-Editor Images
        if (!is_dir(storage_path('app/' . config('filepaths.ckeditorImagePath.internal_path')))) {
            mkdir(storage_path('app/' . config('filepaths.ckeditorImagePath.internal_path')), 0777, true);
        }

        // For Applications
        if (!is_dir($basePath . '/' . Application::STORAGE_DIRECTORY)) {
            mkdir($basePath . '/' . Application::STORAGE_DIRECTORY, 0777, true);
        }
    }
}
