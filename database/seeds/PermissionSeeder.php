<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:clear');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'authorization']);
        Permission::create(['name' => 'user.*']);
    }
}
