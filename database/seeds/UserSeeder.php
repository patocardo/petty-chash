<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Codigitar',
            'email' => getenv('SUDO_EMAIL'),
            'password' => bcrypt(getenv('SUDO_PASS'))
        ]);
        $superAdmin = Role::where('name', 'Super Admin')->first();
        $user->assignRole($superAdmin);
    }
}
