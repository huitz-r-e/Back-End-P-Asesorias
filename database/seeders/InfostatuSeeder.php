<?php

namespace Database\Seeders;

use App\Models\Infostatu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfostatuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status1 = new Infostatu();
        $status1->estado='ACTIVO';
        $status1->descripcion='Cualquier usuario se encuentra activo en la aplicación trabajando.';
        $status1->save();

        $status2 = new Infostatu();
        $status2->estado='GRADUADO';
        $status2->descripcion='El becario ha terminado sus estudios universitarios.';
        $status2->save();

        $status3 = new Infostatu();
        $status3->estado='BAJA TEMPORAL';
        $status3->descripcion='Suspensión del apoyo de beca al estudiante.';
        $status3->save();

        $status4 = new Infostatu();
        $status4->estado='BAJA DEFINITIVA';
        $status4->descripcion='Se ha dado de baja para siempre al becario.';
        $status4->save();

        $status5 = new Infostatu();
        $status5->estado='TITULADO';
        $status5->descripcion='El becario se ha titulado en su universidad.';
        $status5->save();

        $status6 = new Infostatu();
        $status6->estado='NO-ACTIVO';
        $status6->descripcion='No se';
        $status6->save();

        $status7 = new Infostatu();
        $status7->estado='TRABAJANDO';
        $status7->descripcion='No se';
        $status7->save();

        $status8 = new Infostatu();
        $status8->estado='NO TRABAJA';
        $status8->descripcion='No se';
        $status8->save();

        $status9 = new Infostatu();
        $status9->estado='FUNCIONANDO';
        $status9->descripcion='No se';
        $status9->save();

        $status10 = new Infostatu();
        $status10->estado='PROCESO DE CONSTRUCCION';
        $status10->descripcion='No se';
        $status10->save();

        $status11 = new infostatu();
        $status11->estado='SUSPENDIDO';
        $status11->descripcion='La cuenta de cualquier usuario ha sido bloqueada.';
        $status11->save();

        $status12 = new Infostatu();
        $status12->estado='EN PROCESO';
        $status12->descripcion='Cualquier trámite se encuentra en proceso.';
        $status12->save();

        $status13 = new Infostatu();
        $status13->estado='REVISADO';
        $status13->descripcion='La información de solicitud de beca ha sido analizada.';
        $status13->save();

        $status14 = new Infostatu();
        $status14->estado='FINALIZADO';
        $status14->descripcion='El proceso de postulación a beca ha concluido.';
        $status14->save();

        $status15 = new Infostatu();
        $status15->estado='APROBADA';
        $status15->descripcion='La beca ha sido aprobada por cubrir el perfil.';
        $status15->save();

        $status16 = new Infostatu();
        $status16->estado='RECHAZADA';
        $status16->descripcion='La beca ha sido rechazada por no cubrir el perfil.';
        $status16->save();
    }
}
