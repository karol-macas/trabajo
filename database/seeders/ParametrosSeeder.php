<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parametro;

class ParametrosSeeder extends Seeder
{
    public function run()
    {
        $parametros = [
            [
                'nombre' => 'No Participar Eventos Scrum',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Registrar Actividades Diarias Scrum',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Atencion al Cliente',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Crear Instructivos Soluciones',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Crear Manuales de Usuario',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Soportes Sin Ticket',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Identificar Errores Operativos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Enviar Ticket a Tics Sin Revisión',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Documentar Errores',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Cumplir Procesos De Capacitación',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Errores de Documentación Pag,DPF,Apertura,Contratos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Parametrización',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Retrazos de Fecha de Entrega de Productos',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Retrazo de Soportes',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Mala Respuesta en Tickets',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Ingreso Al Area Tics Sin Autorización',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Entregas De Soluciones Sin Firmas De Recibido',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Documentar Errores De Estructuras',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'No Comunicar Errores Contables',
                'departamento_id' => 2,
            ],
            [
                'nombre' => 'Realizar Ajustes Contables Sin Autorización',
                'departamento_id' => 2,
            ],
           


        ];

        foreach ($parametros as $parametro) {
            $parametroModel = new Parametro();
            $parametroModel->fill($parametro);
            $parametroModel->save();
        }
    }
}
