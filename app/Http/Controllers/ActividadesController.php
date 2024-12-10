<?php

/*****************************************************
 * Nombre del Proyecto: ERP 
 * Modulo: Actividades
 * Version: 1.0
 * Desarrollado por: Karol Macas
 * Fecha de Inicio: 
 * Ultima Modificación: 
 ****************************************************/

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Actividades;
use App\Models\Empleados;
use App\Models\Departamento;
use App\Models\Cliente;
use App\Models\Cargos;
use App\Models\Supervisor;

use Illuminate\Support\Facades\Auth;


class ActividadesController extends Controller
{
    public function index(Request $request)
    {

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el empleado seleccionado del filtro (si lo hay)
        $empleadoId = $request->input('empleado_id');

        // Obtener todas las actividades basadas en el rol del usuario
        $actividades = Actividades::with('empleado', 'cliente', 'departamento')
            ->when($user->isEmpleado(), function ($query) use ($user) {
                // Filtrar por empleado autenticado
                return $query->where('empleado_id', $user->empleado->id);
            }, function ($query) use ($empleadoId) {
                // Para administradores: filtrar por empleado si está seleccionado
                if ($empleadoId) {
                    return $query->where('empleado_id', $empleadoId);
                }
                return $query;
            })
            ->orderBy('created_at', 'desc') // Ordenar por las más recientes primero
            ->paginate(10);

        // Obtener la lista de empleados (solo si es necesario)
        $empleados = Empleados::all();

        return view('Actividades.indexActividades', compact('actividades', 'empleados'));
    }


    public function create()
    {
        $user = Auth::user();
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $clientes = Cliente::all();
        $cargos = Cargos::all();
        

        return view('Actividades.createActividades', compact('empleados', 'departamentos', 'clientes', 'cargos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de los demás campos de la actividad
        $validated = $request->validate([
            'cliente_id' => 'nullable|string|max:255',
            'descripcion' => 'required|string|max:255',
            'codigo_osticket' => 'nullable|string|max:255',
            'semanal_diaria' => 'required|string|in:SEMANAL,DIARIO',
            'fecha_inicio' => 'required|date',
            'avance' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required|string|in:PENDIENTE,FINALIZADO',
            'tiempo_estimado' => 'required|integer',
            'repetitivo' => 'required|boolean',
            'prioridad' => 'required|string|in:ALTA,MEDIA,BAJA',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'error' => 'required|string|in:CLIENTE,SOFTWARE,MEJORA ERROR,DESARROLLO,OTRO',
        ]);


        // Crea la actividad
        $actividad = new Actividades();
        $actividad->cliente_id = $request->input('cliente_id');
        $actividad->empleado_id = $request->input('empleado_id');
        $actividad->descripcion = $request->input('descripcion');
        $actividad->codigo_osticket = $request->input('codigo_osticket');
        $actividad->semanal_diaria = $request->input('semanal_diaria');
        $actividad->fecha_inicio = now(); // Esto guardará la fecha y hora actuales
        $actividad->avance = 0;
        $actividad->observaciones = $request->input('observaciones');
        $actividad->estado = 'PENDIENTE';
        $actividad->tiempo_estimado = $request->input('tiempo_estimado');
        $actividad->repetitivo = $request->input('repetitivo');
        $actividad->prioridad = $request->input('prioridad');
        $actividad->departamento_id = $request->input('departamento_id');
        $actividad->cargo_id = $request->input('cargo_id');

        
        $actividad->error = $request->input('error');
        $actividad->save();


        return redirect()->route('actividades.indexActividades')->with('success', 'Actividad creada con éxito.');
    }

    public function show($id)
    {
        $actividades = Actividades::findOrFail($id);
        return view('Actividades.show', compact('actividades'));
    }

    public function edit($id)
    {
        $actividades = Actividades::findOrFail($id);
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $clientes = Cliente::all();
        $cargos = Cargos::all();
       
        return view('Actividades.editActividades', compact('actividades', 'empleados', 'departamentos', 'clientes', 'cargos'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'cliente_id' => 'nullable|string|max:255',
            'empleado_id' => 'required|exists:empleados,id',
            'descripcion' => 'required|string|max:255',
            'codigo_osticket' => 'nullable|string|max:255',
            'semanal_diaria' => 'required|string|in:SEMANAL,DIARIO',
            'fecha_inicio' => 'required|date',
            'avance' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required|string|in:EN CURSO,FINALIZADO,PENDIENTE',
            'tiempo_estimado' => 'required|integer',
            'tiempo_real_horas' => 'nullable|integer',
            'tiempo_real_minutos' => 'nullable|integer',
            'fecha_fin' => 'nullable|date',
            'repetitivo' => 'required|boolean',
            'prioridad' => 'required|string|in:ALTA,MEDIA,BAJA',
            'departamento_id' => 'required|exists:departamentos,id',
            'cargo_id' => 'required|exists:cargos,id',
            'error' => 'required|string|in:CLIENTE,SOFTWARE,MEJORA ERROR,DESARROLLO,OTRO',
        ]);

        $actividades = Actividades::findOrFail($id);
        $actividades->fill($validated);
        $actividades->save();

        return redirect()->route('actividades.indexActividades')->with('success', 'Actividad actualizada con éxito.');
    }

    public function updateAvance(Request $request, $id)
    {
        // Obtener la actividad
        $actividad = Actividades::findOrFail($id);

        // Verificar si el avance ya es 100; si es así, no permitir edición y mostrar un mensaje
        if ($actividad->avance == 100) {
            return redirect()->route('actividades.indexActividades')
                ->withErrors(['error' => 'La actividad ya está finalizada y no puede ser editada.']);
        }

        // Validar el avance
        $validated = $request->validate([
            'avance' => 'required|numeric|min:0|max:100',
        ]);

        $actividad->avance = $validated['avance'];

        // Cambiar el estado según el avance
        if ($actividad->avance == 0) {
            $actividad->estado = 'PENDIENTE';
            $actividad->tiempo_inicio = null;  // Reiniciar tiempo inicio si se vuelve a pendiente
        } elseif ($actividad->avance > 0 && $actividad->avance < 100) {
            $actividad->estado = 'EN CURSO';

            // Si no hay fecha de inicio, se registra la fecha actual como tiempo_inicio
            if (is_null($actividad->tiempo_inicio)) {
                $actividad->tiempo_inicio = now();
            }
        } elseif ($actividad->avance == 100) {
            $actividad->estado = 'FINALIZADO';

            // Registrar la fecha de finalización
            $actividad->fecha_fin = now();

            if ($actividad->tiempo_inicio) {
                $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
                $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');

                $duracionMinutos = $fin->diffInMinutes($inicio);
                $horas = floor($duracionMinutos / 60);
                $minutos = $duracionMinutos % 60;

                // Guardar el tiempo real en horas y minutos
                $actividad->tiempo_real_horas = $horas;
                $actividad->tiempo_real_minutos = $minutos;

                Log::info("Tiempo real calculado: {$horas} horas, {$minutos} minutos.");
            } else {
                return redirect()->back()->withErrors('No se puede finalizar una actividad sin fecha de inicio.');
            }
        }

        $actividad->save();

        return redirect()->route('actividades.indexActividades')->with('success', 'Avance y estado actualizados con éxito.');
    }


    public function startCounter($id)
    {

        $actividad = Actividades::findOrFail($id);

        // Solo iniciar el contador si el estado actual es "PENDIENTE"

        if ($actividad->estado === 'PENDIENTE') {

            $actividad->estado = 'EN CURSO';

            $actividad->fecha_inicio = now(); // Fecha actual para iniciar el contador

            $actividad->save();

            return redirect()->route('actividades.indexActividades')->with('success', 'El contador ha iniciado.');
        }

        return redirect()->route('actividades.indexActividades')->withErrors('El contador solo puede iniciarse si la actividad está pendiente.');
    }

    public function updateEstado(Request $request, $id)
    {
        // Validar el estado
        $request->validate([
            'estado' => 'required|in:EN CURSO,FINALIZADO',
        ]);

        // Buscar la actividad por su ID
        $actividad = Actividades::findOrFail($id);

        // Actualizar el estado
        $actividad->estado = $request->estado;
        $actividad->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('actividades.indexActividades')->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $actividad = Actividades::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.indexActividades')->with('success', 'Actividad eliminada con éxito.');
    }
}
