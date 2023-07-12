<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol1 = new Role();
        $rol1->rol="ADMINISTRADOR";
        $rol1->descripcion="Gestiona y controla toda la aplicacion";
        $rol1->save();

        $rol2 = new Role();
        $rol2->rol="EXPERTO";
        $rol2->descripcion="Revisa y aprueba los voucher de pago";
        $rol2->save();

        $rol3 = new Role();
        $rol3->rol="ESTUDIANTE";
        $rol3->descripcion="Solicita el tramide de la credencial";
        $rol3->save(); 
    }
}
