<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();
       
        if (! Role::where('name', 'member')->first()) {
            Role::create([
                'name' => 'member',
                'label' => 'Member',
            ]);
            Role::create([
                'name' => 'admin',
                'label' => 'Admin',
            ]);
            Role::create([
               'name' => 'supervisor',
               'label' => 'Teacher Supervisor',
            ]);
            Role::create([
               'name' => 'teacher',
               'label' => 'Teacher',
            ]);
            Role::create([
               'name' => 'webstaff',
               'label' => 'WebStaff',
            ]);
            
        }
    }
}
