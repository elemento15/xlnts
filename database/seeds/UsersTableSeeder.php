<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
        	[
        		'name' => 'Administrador',
        		'email' => 'admin@example.com',
        		'role_id' => Role::getIdByCode('ADM'),
        		'password' => bcrypt('sysadministrator'),
        		'active' => true
        	],[
                'name' => 'Juan Perez',
                'email' => 'juan@example.com',
                'role_id' => Role::getIdByCode('OPE'),
                'password' => bcrypt('operator'),
                'active' => true
            ]
        ];

        foreach ($users as $key => $item) {
        	User::create($item);
        }
    }
}
