<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleados;
use Illuminate\Support\Facades\Hash;
use App\Models\Departamento;
use App\Models\Cargos;
use App\Models\Rubro;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class EmpleadosController extends Controller
{
    public function index()
    {
        $empleados = Empleados::with('departamento')
            ->latest()
            ->paginate(10);
        return view('Empleados.indexEmpleados', compact('empleados'));
    }

    public function create()
    {
        $empleado = new Empleados();
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        $supervisores = Empleados::where('es_supervisor', true)->get();
        $cargos = Cargos::all();
        $rubros = Rubro::all();



        return view('empleados.createEmpleados', compact('empleado', 'departamentos', 'cargos', 'rubros', 'supervisores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre1' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'cedula' => 'required|string|max:10|unique:empleados',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'celular' => 'required|string|max:15',
            'correo_institucional' => 'required|email|unique:empleados,correo_institucional',
            'departamento_id' => 'required|exists:departamentos,id',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx',
            'contrato' => 'nullable|file|mimes:pdf,jpg,png',
            'contrato_confidencialidad' => 'nullable|file|mimes:pdf,jpg,png',
            'contrato_consentimiento' => 'nullable|file|mimes:pdf,jpg,png',
            'fecha_ingreso' => 'required|date',
            'cargo_id' => 'required|exists:cargos,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'es_supervisor' => 'nullable|boolean',
            'jornada_laboral' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_conclusion_contrato' => 'nullable|date',
            'terminacion_contrato' => 'nullable|string|max:255',
            'fecha_recontratacion' => 'nullable|date',
            'rubros' => 'nullable|array',  // Asegúrate de que los rubros sean opcionales
            'rubros.*' => 'exists:rubros,id', // Validar que los rubros existan en la base de datos
            'monto' => 'nullable|array',  // Los montos asociados a los rubros
            'monto.*' => 'numeric',  // Asegúrate de que los montos sean números
        ]);

        //Crear el empleado
        $empleados = new Empleados($validated);



        // Crear el usuario asociado al empleado
        $user = User::create([
            'name' => $validated['nombre1'] . ' ' . $validated['apellido1'],
            'email' => $validated['correo_institucional'],
            'password' => Hash::make($validated['cedula']),
            'role' => 'empleado',
        ]);


        $empleados->user_id = $user->id;

        // Subir archivos al disco 'public'
        if ($request->hasFile('curriculum')) {
            $empleados->curriculum = $request->file('curriculum')->store('curriculums', 'public');
        }

        if ($request->hasFile('contrato')) {
            $empleados->contrato = $request->file('contrato')->store('contratos_empleados', 'public');
        }

        if ($request->hasFile('contrato_confidencialidad')) {
            $empleados->contrato_confidencialidad = $request->file('contrato_confidencialidad')->store('contratos_confidencialidad', 'public');
        }

        if ($request->hasFile('contrato_consentimiento')) {
            $empleados->contrato_consentimiento = $request->file('contrato_consentimiento')->store('contratos_consentimiento', 'public');
        }

        $empleados->save();

        // Si el empleado es supervisor, registrar en la tabla 'supervisores'
        if ($empleados->es_supervisor) {
            $supervisor = new Supervisor([
                'empleado_id' => $empleados->id,
                'nombre_supervisor' => $empleados->nombre1 . ' ' . $empleados->apellido1,
                'departamento_id' => $empleados->departamento_id,  // Asignar el departamento del empleado
            ]);
            $supervisor->save(); // Guardamos el supervisor

            // Asignamos este supervisor a todos los departamentos correspondientes
            Departamento::where('id', $empleados->departamento_id)->update([
                'supervisor_id' => $empleados->id
            ]);
        }

        if ($request->has('supervisor_id') && $request->input('supervisor_id') != '') {
            $empleados->supervisor_id = $request->input('supervisor_id');
            $empleados->save();
        }



        $empleadoId = $empleados->id;
        $rubros = $request->input('rubros', []);
        $montos = $request->input('montos', []);

        // Asignar rubros con sus montos
        foreach ($rubros as $rubroId) {
            $monto = $montos[$rubroId] ?? 0; // Obtener el monto asociado al rubro o 0 si no existe

            // Guardar en la base de datos (tabla empleado_rubro)
            DB::table('empleado_rubro')->insert([
                'empleado_id' => $empleadoId,
                'rubro_id' => $rubroId,
                'monto' => $monto,
            ]);
        }


        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado creado con éxito.');
    }

    public function getSupervisoresPorDepartamento($departamento_id)
    {
        // Obtener los supervisores que pertenecen al departamento seleccionado
        $supervisores = Empleados::where('departamento_id', $departamento_id)
            ->where('es_supervisor', true)
            ->get();

        return response()->json($supervisores);
    }

    public function getSupervisores($departamento_id)
    {
        $supervisores = Supervisor::where('departamento_id', $departamento_id)->get();
        return response()->json(['supervisores' => $supervisores]);
    }

    public function show($id)
    {
        $empleados = Empleados::find($id);
        $empleados = Empleados::with('departamento')->findOrFail($id);
        $empleados = Empleados::with('rubros')->findOrFail($id);
        $empleados = Empleados::with('cargo')->findOrFail($id);
        $empleados = Empleados::with('supervisor')->findOrFail($id);

        // Calcular los totales de ingresos y egresos
        $totalIngreso = 0;
        $totalEgreso = 0;

        foreach ($empleados->rubros as $rubro) {
            if ($rubro->tipo_rubro == 'ingreso') {
                $totalIngreso += $rubro->pivot->monto;  // Acceder al monto a través del campo 'pivot'
            } elseif ($rubro->tipo_rubro == 'egreso') {
                $totalEgreso += $rubro->pivot->monto;
            }
        }

        return view('Empleados.show',  compact('empleados', 'totalIngreso', 'totalEgreso'));
    }

    public function edit($id)
    {
        $empleados = Empleados::find($id);
        $departamentos = Departamento::all();
        $supervisores = Supervisor::all();
        $cargos = Cargos::all();
        $rubros = Rubro::all();
        return view('empleados.editEmpleados', compact('empleados', 'departamentos', 'supervisores', 'cargos', 'rubros'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre1' => 'required|string|max:255',
            'apellido1' => 'required|string|max:255',
            'nombre2' => 'nullable|string|max:255',
            'apellido2' => 'nullable|string|max:255',
            'cedula' => [
                'required',
                'string',
                'max:10',
                Rule::unique('empleados')->ignore($id),
            ],
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:15',
            'celular' => 'required|string|max:15',
            'correo_institucional' => 'required|string|email|max:255|unique:empleados,correo_institucional,' . $id,
            'departamento_id' => 'required|exists:departamentos,id',
            'curriculum' => 'nullable|file|mimes:pdf,doc,docx',
            'contrato' => 'nullable|file|mimes:pdf,jpg,png',
            'contrato_confidencialidad' => 'nullable|file|mimes:pdf,jpg,png',
            'contrato_consentimiento' => 'nullable|file|mimes:pdf,jpg,png',
            'fecha_ingreso' => 'required|date',
            'cargo_id' => 'required|exists:cargos,id',
            'es_supervisor' => 'nullable|boolean',
            'jornada_laboral' => 'required|string|max:255',
            'fecha_contratacion' => 'required|date',
            'fecha_conclusion_contrato' => 'nullable|date',
            'terminacion_contrato' => 'nullable|string|max:255',
            'fecha_recontratacion' => 'nullable|date',
            'rubros' => 'nullable|array',
            'rubros.*' => 'exists:rubros,id',
            'monto_rubro' => 'nullable|array',
            'monto_rubro.*' => 'numeric',
        ]);

        $empleados = Empleados::findOrFail($id);
        $empleados->fill($validated);

        // Subir nuevos archivos y eliminar los antiguos si existen
        if ($request->hasFile('curriculum')) {
            if ($empleados->curriculum) {
                Storage::disk('public')->delete($empleados->curriculum);
            }
            $empleados->curriculum = $request->file('curriculum')->store('curriculums', 'public');
        }

        if ($request->hasFile('contrato')) {
            if ($empleados->contrato) {
                Storage::disk('public')->delete($empleados->contrato);
            }
            $empleados->contrato = $request->file('contrato')->store('contratos_empleados', 'public');
        }

        if ($request->hasFile('contrato_confidencialidad')) {
            if ($empleados->contrato_confidencialidad) {
                Storage::disk('public')->delete($empleados->contrato_confidencialidad);
            }
            $empleados->contrato_confidencialidad = $request->file('contrato_confidencialidad')->store('contratos_confidencialidad', 'public');
        }

        if ($request->hasFile('contrato_consentimiento')) {
            if ($empleados->contrato_consentimiento) {
                Storage::disk('public')->delete($empleados->contrato_consentimiento);
            }
            $empleados->contrato_consentimiento = $request->file('contrato_consentimiento')->store('contratos_consentimiento', 'public');
        }

        $empleados->save();

        // Si el empleado se convierte en supervisor, registrar en la tabla 'supervisores'
        if ($empleados->es_supervisor && !$empleados->supervisor) {
            $supervisor = new Supervisor([
                'empleado_id' => $empleados->id,
                'nombre_supervisor' => $empleados->nombre1 . ' ' . $empleados->apellido1,
                'departamento_id' => $empleados->departamento_id,
            ]);
            $supervisor->save();

            // Asignar el supervisor automáticamente a su departamento
            Departamento::where('id', $empleados->departamento_id)->update([
                'supervisor_id' => $empleados->id,
            ]);
        }

        // Asignar rubros con sus montos
        if ($request->filled('rubros') && $request->filled('monto_rubro')) {
            $rubros = $request->input('rubros');
            $montos = $request->input('monto_rubro');

            // Sincronizar los rubros con sus montos en la tabla pivote
            $empleados->rubros()->sync(
                collect($rubros)->mapWithKeys(function ($rubroId) use ($montos) {
                    return [$rubroId => ['monto' => $montos[$rubroId] ?? 0]]; // Valor predeterminado si no está presente
                })->toArray()
            );
        }


        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado actualizado con éxito.');
    }

    public function getEmployeeDetails($id)
    {
        $empleado = Empleados::with('departamento', 'cargo', 'supervisor')->findOrFail($id);

        return response()->json([
            'departamento' => $empleado->departamento->nombre ?? 'N/A',
            'cargo' => $empleado->cargo->nombre_cargo ?? 'N/A',
            'supervisor' => $empleado->supervisor->nombre_supervisor ?? 'N/A',
        ]);
    }



    public function destroy($id)
    {
        $empleados = Empleados::findOrFail($id);

        // Eliminar los archivos asociados antes de eliminar el empleado
        if ($empleados->curriculum) {
            Storage::disk('public')->delete($empleados->curriculum);
        }

        if ($empleados->contrato) {
            Storage::disk('public')->delete($empleados->contrato);
        }

        if ($empleados->contrato_confidencialidad) {
            Storage::disk('public')->delete($empleados->contrato_confidencialidad);
        }

        if ($empleados->contrato_consentimiento) {
            Storage::disk('public')->delete($empleados->contrato_consentimiento);
        }

        $empleados->delete();

        return redirect()->route('empleados.indexEmpleados')->with('success', 'Empleado eliminado con éxito.');
    }
}
