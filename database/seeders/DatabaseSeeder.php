<?php

namespace Database\Seeders;

use App\Models\ApiKey;
use App\Models\Application;
use App\Models\ChatBotKnowledgeBase;
use App\Models\Client;
use App\Models\ClientAttachment;
use App\Models\ContactPageQuery;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Menu;
use App\Models\Job;
use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
use App\Models\Post;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SearchStatistic;
use App\Models\Settings;
use App\Models\SliderImage;
use App\Models\StaticBlock;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
        $this->createMenus();        

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
        $this->createSettings();
        
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
        
        $this->createStaticBlocks();
        
        // For Slider Images
        $this->createSliderImages();

        for ($i = 1; $i <= 6; $i++) {
            $this->createMediaLibraries($i);
        }

        // For Client
        Client::factory(4)->create();

        // For Client Attachment
        ClientAttachment::factory(20)->create();
    }


    /**
     * Create directories for storing assets in public directory of the application.
     *
     * @return void
     */
    private function makeDirectories()
    {

        $basePath = config('settings.storage_disk_base_path');

        // For User Profile
        if (!is_dir($basePath . User::STORAGE_DIRECTORY)) {
            mkdir($basePath . User::STORAGE_DIRECTORY, 0777, true);
        }

        // For Page Images
        if (!is_dir($basePath . Page::STORAGE_DIRECTORY)) {
            mkdir($basePath . Page::STORAGE_DIRECTORY, 0777, true);
        }

        // For Posts Images
        if (!is_dir($basePath . Post::STORAGE_DIRECTORY)) {
            mkdir($basePath . Post::STORAGE_DIRECTORY, 0777, true);
        }

        // For Job Images
        if (!is_dir($basePath . Job::STORAGE_DIRECTORY)) {
            mkdir($basePath . Job::STORAGE_DIRECTORY, 0777, true);
        }

        // For Documents Images
        if (!is_dir($basePath . Document::STORAGE_DIRECTORY)) {
            mkdir($basePath . Document::STORAGE_DIRECTORY, 0777, true);
        }

        // For CK-Editor Images
        if (!is_dir(storage_path('app/' . config('filepaths.ckeditorImagePath.internal_path')))) {
            mkdir(storage_path('app/' . config('filepaths.ckeditorImagePath.internal_path')), 0777, true);
        }

        // For Applications
        if (!is_dir($basePath . Application::STORAGE_DIRECTORY)) {
            mkdir($basePath . Application::STORAGE_DIRECTORY, 0777, true);
        }

        // For Slider Images
        if (!is_dir($basePath . SliderImage::STORAGE_DIRECTORY)) {
            mkdir($basePath . SliderImage::STORAGE_DIRECTORY, 0777, true);
        }

        if (!is_dir($basePath . Client::SIGNATURE_DIR)) {
            mkdir($basePath . Client::SIGNATURE_DIR, 0777, true);
        }

        if (!is_dir($basePath . ClientAttachment::DIR)) {
            mkdir($basePath . ClientAttachment::DIR, 0777, true);
        }
    }
    
    /**
     * createMenus
     *
     * @return void
     */
    private function createMenus()
    {
        Menu::create([
            'name' => 'Access Market Portals',
            'identifier' => 'top_menu_1',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'Password reset GBB', 'anchor' => '#'],
                ['id' => 2, 'title' => 'API portal', 'anchor' => '#'],
                ['id' => 3, 'title' => 'WA GBB', 'anchor' => '#'],
                ['id' => 4, 'title' => 'WEMS MPI', 'anchor' => '#'],
                ['id' => 5, 'title' => 'OPDMS', 'anchor' => '#'],
                ['id' => 6, 'title' => 'Market Portal', 'anchor' => '#'],
                ['id' => 7, 'title' => 'Market Portals Help', 'anchor' => '#'],
                ['id' => 8, 'title' => 'NOS', 'anchor' => '#'],
                ['id' => 9, 'title' => 'MSATS and B2B Hub', 'anchor' => '#'],
                ['id' => 10, 'title' => 'Web Exchanger (WEX)', 'anchor' => '#'],
                ['id' => 11, 'title' => 'Participant Services Portal', 'anchor' => '#'],
                ['id' => 12, 'title' => 'DER Register Installer Portal', 'anchor' => '#'],
                ['id' => 13, 'title' => 'System Management MPI', 'anchor' => '#'],
                ['id' => 14, 'title' => 'Market Information System (MIS)', 'anchor' => '#'],
                ['id' => 15, 'title' => 'Market Information Bulletin Board (MIBB)', 'anchor' => '#'],
            ])
        ]);
        Menu::create([
            'name' => 'Header Menu 2',
            'identifier' => 'top_menu_2',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'Market Renewal', 'anchor' => '#'],
                ['id' => 2, 'title' => 'Sector Participants', 'anchor' => '#'],
                ['id' => 3, 'title' => 'Corporate MO', 'anchor' => '#'],
            ])
        ]);
        Menu::create([
            'name' => 'Main Menu',
            'identifier' => 'main_menu',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'Learn', 'anchor' => '#'],
                ['id' => 2, 'title' => 'Get Involved', 'anchor' => '#'],
                ['id' => 3, 'title' => 'Power Data', 'anchor' => '#'],
                ['id' => 4, 'title' => 'Powering Tomorrow', 'anchor' => '#'],
            ])
        ]);
        Menu::create([
            'name' => 'Site Links',
            'identifier' => 'footer_menu_1',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'About', 'anchor' => '#'],
                ['id' => 2, 'title' => 'Library', 'anchor' => '#'],
                ['id' => 3, 'title' => 'Careers', 'anchor' => '#'],
                ['id' => 4, 'title' => 'Contact', 'anchor' => '#'],
                ['id' => 5, 'title' => 'Login', 'anchor' => '#'],
            ])
        ]);
        Menu::create([
            'name' => 'Other Links',
            'identifier' => 'footer_menu_2',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'News & Events', 'anchor' => '#'],
                ['id' => 2, 'title' => 'Notices', 'anchor' => '#'],
                ['id' => 3, 'title' => 'Ipps', 'anchor' => '#'],
                ['id' => 4, 'title' => 'Tenders', 'anchor' => '#'],
                ['id' => 5, 'title' => 'Downloads', 'anchor' => '#'],
            ])
        ]);
        Menu::create([
            'name' => 'Site Information',
            'identifier' => 'footer_menu_3',
            'theme' => 'theme1',
            'active' => 1,
            'submenu_json' => json_encode([
                ['id' => 1, 'title' => 'Sitemap', 'anchor' => '#'],
                ['id' => 2, 'title' => 'Terms', 'anchor' => '#'],
                ['id' => 3, 'title' => 'Privacy', 'anchor' => '#'],
                ['id' => 4, 'title' => 'Faqs', 'anchor' => '#'],
            ])
        ]);
    }

    private function createStaticBlocks()
    {
        StaticBlock::create(
            [
                'name' => 'Contact Us',
                'contents' => '<p><strong>Email</strong>: info@mo.gov.pk</p>

                <p><strong>Contact</strong>:&nbsp;051-111-922-772</p>
                
                <p><strong>Address</strong>:&nbsp;Shaheen Plaza, Plot No. 73-West, Fazl-Ul-Haq Road, Blue Area, Islamabad, Pakistan.</p>'
            ]
        );
    }

    private function createSliderImages()
    {
        SliderImage::create([
            'slot_one' => 'Market Operator',
            'slot_two' => 'Bridges Between Single Buyer to Market.',
            'url' => '#',
            'order' => 1,
            'image' => 'slider1.png'
        ]);
        SliderImage::create([
            'slot_one' => 'Market Operator 2',
            'slot_two' => 'Bridges Between Single Buyer to Market.',
            'url' => '#',
            'order' => 2,
            'image' => 'slider2.png'
        ]);
        SliderImage::create([
            'slot_one' => 'Market Operator 3',
            'slot_two' => 'Bridges Between Single Buyer to Market.',
            'url' => '#',
            'order' => 3,
            'image' => 'slider3.png'
        ]);

        $basePath = config('settings.storage_disk_base_path') . SliderImage::STORAGE_DIRECTORY;

        copy(public_path('slider_images/slider1.png'), $basePath . 'slider1.png');
        copy(public_path('slider_images/slider2.png'), $basePath . 'slider2.png');
        copy(public_path('slider_images/slider3.png'), $basePath . 'slider3.png');
    }

    private function createSettings()
    {
        Settings::factory(1)->create([
            'name' => 'current_theme',
            'value' => 'theme1'
        ]);
        Settings::factory(1)->create([
            'name' => 'notification_emails',
            'value' => 'test@nxb.com.pk,testing@nxb.com.pk'
        ]);
    }

    private function createMediaLibraries($counter)
    {
        
        $name = 'Farewell Party for Retiring Employees ' . $counter;
        $slug = Str::slug($name);
        
        $mediaLibrary = MediaLibrary::factory()->create([
            'name' => $name,
            'description' => 'This is test description for ' . $name,
            'directory' => $slug
        ]);

        $basePath = config('settings.storage_disk_base_path') . MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory;
        $filename = 'media' . $counter . '.png';

        if (!is_dir($basePath)) {
            mkdir($basePath, 0777, true);

            copy(public_path('media_images/' . $filename), $basePath . '/' . $filename);
        }

        MediaLibraryFile::create([
            'file' => $filename,
            'featured' => 1,
            'media_library_id' => $mediaLibrary->id,
        ]);
    }
}
