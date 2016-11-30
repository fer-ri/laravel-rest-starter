<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = App\Models\Role::create(['name' => 'super-admin']);

        $user = App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
            'password' => 'secret',
            'activated_at' => new DateTime,
        ]);

        $user->attachRole($role);
    }
}
