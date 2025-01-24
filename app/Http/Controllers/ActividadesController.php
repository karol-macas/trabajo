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

use Carbon\Carbon;


class ActividadesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el empleado seleccionado del filtro (si lo hay)
        $empleadoId = $request->input('empleado_id');

        // Obtener los filtros del request
        $filtro = $request->input('filtro', 'semana'); // Por defecto, "semana"
        $semanaSeleccionada = $request->input('semana', 0); // Por defecto, semana actual
        $mesSeleccionado = $request->input('mes', now()->format('Y-m')); // Mes actual como predeterminado

        // Inicializar la consulta de actividades
        $actividadesQuery = Actividades::with('empleado', 'cliente', 'departamento')
            ->when($user->isEmpleado(), function ($query) use ($user) {
                // Filtrar por empleado autenticado
                return $query->where('empleado_id', $user->empleado->id);
            }, function ($query) use ($empleadoId) {
                // Para administradores: filtrar por empleado si está seleccionado
                if ($empleadoId) {
                    return $query->where('empleado_id', $empleadoId);
                }
                return $query;
            });

        // Aplicar filtro por semana o mes
        if ($filtro === 'semana') {
            $inicioSemana = now()->startOfWeek()->subWeeks($semanaSeleccionada);
            $finSemana = now()->endOfWeek()->subWeeks($semanaSeleccionada);

            $actividadesQuery->whereBetween('created_at', [$inicioSemana, $finSemana]);
        } elseif ($filtro === 'mes') {
            $inicioMes = Carbon::parse($mesSeleccionado)->startOfMonth();
            $finMes = Carbon::parse($mesSeleccionado)->endOfMonth();

            $actividadesQuery->whereBetween('created_at', [$inicioMes, $finMes]);
        }

        // Obtener actividades filtradas
        $actividades = $actividadesQuery->orderBy('created_at', 'desc')->get();

        // Obtener la lista de empleados
        $empleados = Empleados::all();

        // Contar las actividades por estado
        $enCursoCount = $actividades->where('estado', 'EN CURSO')->count();
        $pendienteCount = $actividades->where('estado', 'PENDIENTE')->count();
        $finalizadoCount = $actividades->where('estado', 'FINALIZADO')->count();

        return view('Actividades.indexActividades', compact(
            'actividades',
            'empleados',
            'enCursoCount',
            'pendienteCount',
            'finalizadoCount',
            'filtro',
            'semanaSeleccionada',
            'mesSeleccionado'
        ));
    }



    public function create()
    {
        $user = Auth::user();
        $empleados = Empleados::all();
        $departamentos = Departamento::all();
        $clientes = Cliente::all();
        $cargos = Cargos::all();

        $departamento = null;
        $cargo = null;

        // Si es un administrador, pasar los datos de departamento y cargo
        if (Auth::user()->isAdmin()) {
            // Aquí debes cargar el departamento, cargo y supervisor correspondientes
            $departamento = Departamento::find(1); // Asume que el administrador debe seleccionar un departamento
            $cargo = Cargos::find(1); // Asume que el administrador debe seleccionar un cargo
            $supervisor = Supervisor::find(1); // Ejemplo de supervisor
        }



        return view('Actividades.createActividades', compact('empleados', 'departamentos', 'clientes', 'cargos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validación de los demás campos de la actividad
        $validated = $request->validate([
            'cliente_id' => 'nullable|string|max:255',
            'descripcion' => 'required|string|max:255',
            'codigo_osticket' => 'nullable|url',
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
            'codigo_osticket' => 'nullable|url',
            'semanal_diaria' => 'required|string|in:SEMANAL,DIARIO',
            'fecha_inicio' => 'required|date',
            'avance' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string|max:255',
            'estado' => 'required|string|in:EN CURSO,FINALIZADO,PENDIENTE',
            'tiempo_estimado' => 'required|integer',
            'tiempo_real_horas' => 'nullable|integer',
            'tiempo_real_minutos' => 'nullable|integer',
            'tiempo_acumulado_minutos' => 'nullable|integer',
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

        // Validar si la actividad está en estado PENDIENTE y se intenta finalizar
        if ($actividad->estado === 'PENDIENTE' && $request->input('avance') == 100) {
            return redirect()->route('actividades.indexActividades')
                ->withErrors(['error' => 'No se puede finalizar una actividad que no ha iniciado.']);
        }

        // Validar el avance
        $validated = $request->validate([
            'avance' => 'required|numeric|min:0|max:100',
        ]);

        // Actualizar el avance
        $actividad->avance = $validated['avance'];

        // Manejar los estados según el avance
        if ($actividad->avance == 0) {
            // Si está en pausa (PENDIENTE)
            $actividad->estado = 'PENDIENTE';

            // Calcular y acumular el tiempo transcurrido antes de pausar
            if (!is_null($actividad->tiempo_inicio)) {
                $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
                $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
                $duracionMinutos = $fin->diffInMinutes($inicio);

                $actividad->tiempo_acumulado_minutos += $duracionMinutos;
                $actividad->tiempo_inicio = null; // Reiniciar el tiempo de inicio
            }
        } elseif ($actividad->avance > 0 && $actividad->avance < 100) {
            // Si está en curso (EN CURSO)
            $actividad->estado = 'EN CURSO';

            // Registrar el tiempo de inicio si no está en curso
            if (is_null($actividad->tiempo_inicio)) {
                $actividad->tiempo_inicio = now();
            }
        } elseif ($actividad->avance == 100) {
            // Si finaliza la actividad (FINALIZADO)
            $actividad->estado = 'FINALIZADO';

            // Calcular y acumular el tiempo transcurrido antes de finalizar
            if (!is_null($actividad->tiempo_inicio)) {
                $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
                $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
                $duracionMinutos = $fin->diffInMinutes($inicio);

                $actividad->tiempo_acumulado_minutos += $duracionMinutos;
                $actividad->tiempo_inicio = null; // Reiniciar el tiempo de inicio
            }

            // Registrar la fecha de finalización
            $actividad->fecha_fin = now();

            // Convertir el tiempo acumulado a horas y minutos
            $horas = floor($actividad->tiempo_acumulado_minutos / 60);
            $minutos = $actividad->tiempo_acumulado_minutos % 60;

            $actividad->tiempo_real_horas = $horas;
            $actividad->tiempo_real_minutos = $minutos;
        }

        // Guardar los cambios
        $actividad->save();

        return redirect()->route('actividades.indexActividades')->with('success', 'Avance y estado actualizados con éxito.');
    }

    public function updateObservaciones(Request $request, $id)
    {
        // Obtener la actividad
        $actividad = Actividades::findOrFail($id);

        // Verificar si el avance ya es 100; si es así, no permitir edición y mostrar un mensaje
        if ($actividad->avance == 100) {
            return redirect()->route('actividades.indexActividades')
                ->withErrors(['error' => 'La actividad ya está finalizada y no puede ser editada.']);
        }

        // Validar las observaciones
        $validated = $request->validate([
            'observaciones' => 'nullable|string|max:255',
        ]);

        $actividad->observaciones = $validated['observaciones'];
        $actividad->save();

        return redirect()->route('actividades.indexActividades')->with('success', 'Observaciones actualizadas con éxito.');
    }




    public function startCounter($id)
    {

        $actividad = Actividades::findOrFail($id);

        // Solo iniciar el contador si el estado actual es "PENDIENTE"

        if ($actividad->estado === 'PENDIENTE') {

            $actividad->estado = 'EN CURSO';

            $actividad->fecha_inicio = now(); // Fecha actual para iniciar el contador

            $actividad->save();

            return redirect()->route('Actividades.indexActividades')->with('success', 'El contador ha iniciado.');
        }

        return redirect()->route('Actividades.indexActividades')->withErrors('El contador solo puede iniciarse si la actividad está pendiente.');
    }


    public function updateEstado(Request $request, $id)
    {
        $actividad = Actividades::findOrFail($id);

        // Finalizar actividad
        if ($request->has('finalizar')) {
            // Verificar si la actividad no ha iniciado
            if ($actividad->estado === 'PENDIENTE') {
                return redirect()->route('actividades.indexActividades')
                    ->withErrors(['error' => 'No se puede finalizar una actividad que no ha iniciado.']);
            }

            $actividad->avance = 100;
            $actividad->estado = 'FINALIZADO';
            $actividad->fecha_fin = now();

            // Calcular y acumular tiempo antes de finalizar
            $this->actualizarTiempo($actividad);

            // Convertir tiempo acumulado a horas y minutos
            $horas = floor($actividad->tiempo_acumulado_minutos / 60);
            $minutos = $actividad->tiempo_acumulado_minutos % 60;

            $actividad->tiempo_real_horas = $horas;
            $actividad->tiempo_real_minutos = $minutos;

            $actividad->save();

            return redirect()->route('actividades.indexActividades')->with('success', 'Actividad finalizada con éxito.');
        }

        // Pausar actividad
        if ($request->has('pausar')) {
            $actividad->estado = 'PENDIENTE';

            // Calcular y acumular tiempo antes de pausar
            $this->actualizarTiempo($actividad);

            $actividad->save();

            return redirect()->route('actividades.indexActividades')->with('success', 'Actividad pausada con éxito.');
        }

        // Reanudar actividad
        if ($request->has('reanudar')) {
            if ($actividad->estado === 'PENDIENTE') {
                $actividad->estado = 'EN CURSO';
                $actividad->tiempo_inicio = now();
                $actividad->save();

                return redirect()->route('actividades.indexActividades')->with('success', 'Actividad reanudada con éxito.');
            }

            return redirect()->route('actividades.indexActividades')->withErrors('Solo se pueden reanudar actividades en estado pendiente.');
        }

        return redirect()->route('actividades.indexActividades')->withErrors('Operación no válida.');
    }

    /**
     * Método para calcular y acumular tiempo transcurrido.
     */
    private function actualizarTiempo($actividad)
    {
        if (!is_null($actividad->tiempo_inicio)) {
            $inicio = \Carbon\Carbon::parse($actividad->tiempo_inicio)->setTimezone('America/Guayaquil');
            $fin = \Carbon\Carbon::now()->setTimezone('America/Guayaquil');
            $duracionMinutos = $fin->diffInMinutes($inicio);

            $actividad->tiempo_acumulado_minutos += $duracionMinutos;
            $actividad->tiempo_inicio = null; // Reiniciar tiempo inicio
        }
    }

    public function show($id)
    {
        $actividades = Actividades::with('cliente', 'empleado', 'departamento')->findOrFail($id);

        // Si es una solicitud AJAX, devuelve el parcial
        if (request()->ajax()) {
            return view('actividades.partials.show-content', compact('actividades'));
        }

        // De lo contrario, devuelve la vista completa
        return view('actividades.show', compact('actividades'));
    }




    public function destroy($id)
    {
        $actividad = Actividades::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.indexActividades')->with('success', 'Actividad eliminada con éxito.');
    }
}
