<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AddACLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $adminACL = \App\Role::firstOrCreate(['name'=>'Admin'],['description'=>'Função de Administrador']);
        $gerenteACL = \App\Role::firstOrCreate(['name'=>'Gerente'],['description'=>'Função de Gerente']);

        // User com Role
        $userAdmin = \App\User::find('1');
        $userGerente = \App\User::find('2');

        $userAdmin->roles()->attach($adminACL);
        $userGerente->roles()->attach($gerenteACL);

        // Permissions
        $listUser = \App\Permission::firstOrCreate(['name'=>'list-user'],['description'=>'Listar registros']);
        $createUser = \App\Permission::firstOrCreate(['name'=>'create-user'],['description'=>'Criar usuários']);

        // Role com Permissions
        $gerenteACL->permissions()->attach($listUser);
        $gerenteACL->permissions()->attach($createUser);


        echo "Registro de ACL criados! \n";
    }
}
