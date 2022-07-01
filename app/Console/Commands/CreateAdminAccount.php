<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user for the application.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = [];
        $data['name'] = $this->ask('Enter Name.');
        $data['email'] = $this->ask('Enter admin E-Mail address.');
        $data['password'] = $this->secret('Enter admin password.');

        if ($this->confirm('Do you wish to continue?')) {

            $this->info('Admin account is in process, Please wait.');

            $role = $this->createRole();

            if ($role->permissions->count() === 0) {
                $this->assignPermissions($role->id);
            }

            $this->createAdmin($data, $role->id);

            $this->addApiKeyEntry();

            $this->initSettings();

            $this->info('Admin account has been created successfully! Please login with your credentials.');
        }
        return 0;
    }

    private function createRole()
    {
        return Role::firstOrCreate([
            'name' => 'Administrator'
        ]);
    }

    private function assignPermissions($roleId)
    {
        $permissions = config('permissions');
        foreach( $permissions as $permission ) {
            foreach( $permission['capabilities'] as $capability_name => $capability_display_name){
                Permission::create([
                    'role_id'       => $roleId,
                    'name'          => $permission['name'],
                    'capability'    => $capability_name
                ]);
            }
        }
    }

    private function createAdmin($data, $roleId)
    {
        $user = User::where('email', '=', $data['email'])->first();

        if (! $user) {
            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'active'        => true,
                'designation'   => 'Admin',
                'role_id'       => $roleId,
                'department'    => 1,
                'created_by'    => 0,
                'updated_by'    => 0
            ]);

            $user->password = bcrypt($data['password']);
            $user->save();

            return $user;
        }
    }

    private function addApiKeyEntry()
    {
        if (! (ApiKey::where('name', 'app_key')->first())) {
            ApiKey::create([
                'name'  => 'app_key',
                'value' => 'vxPwTIcOAwUMU1rREvR1h8UPaHGnZtVZGuH7jzWRWaowXyW33tCxiRZfKM4PuXvC6RXWL7xrqTuXVQDCjzRKickhVE0EqP4maCn8vzt8JYQ9hoNuZoTxDVNBLLdP1r6MMMvxKbYknSa5bcD0rHbCU2oCA3419Y9LcfisDQugd8vmp6yUGkw6NEu9V9AsnxThZJNtn1vq'
            ]);
        }
    }

    private function initSettings()
    {
        Settings::firstOrCreate([
            'name'  => 'current_theme',
            'value' => 'theme1'
        ]);
    }
}
