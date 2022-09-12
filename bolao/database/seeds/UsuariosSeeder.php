<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::firstOrCreate(['email'=>'admin@mail.com'],[
            'name'=>'Admin',
            'password'=>Hash::make('123456')
        ]);

        \App\User::firstOrCreate(['email'=>'gerente@mail.com'],[
            'name'=>'Gerente',
            'password'=>Hash::make('123456')
        ]);

        echo "Usuários Criados! \n";
    }
}
