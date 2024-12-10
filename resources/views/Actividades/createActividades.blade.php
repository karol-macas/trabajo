@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2><i class="fas fa-tasks"></i> Crear Nueva Actividad</h2>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('actividades.store') }}" method="POST">
                            @csrf

                            <!-- Selección del Cliente -->
                            <div class="form-group row mb-3">
                                <label for="cliente_id" class="col-md-4 col-form-label text-md-right">Clientes &
                                    Cooperativa</label>
                                <div class="col-md-6">
                                    <select name="cliente_id" class="form-select">
                                        <option value="">Seleccione un cliente & Cooperativa </option>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <!-- Selección del Empleado -->
                            @if (Auth::user()->isAdmin())
                                <div class="form-group row mb-3">
                                    <label for="empleado_id" class="col-md-4 col-form-label text-md-right">Empleado</label>
                                    <div class="col-md-6">
                                        <label for="empleado_id">Seleccione un empleado</label>
                                        <select name="empleado_id" class="form-select" id="empleado_id" required
                                            onchange="updateEmployeeInfo()">
                                            <option value="">Seleccione un empleado</option>
                                            @foreach ($empleados as $empleado)
                                                <option value="{{ $empleado->id }}"
                                                    data-departamento="{{ $empleado->departamento->nombre ?? 'Sin departamento' }}"
                                                    data-departamento-id="{{ $empleado->departamento->id ?? '' }}"
                                                    data-cargo="{{ $empleado->cargo->nombre_cargo ?? 'Sin cargo' }}"
                                                    data-cargo-id="{{ $empleado->cargo->id ?? '' }}"
                                                    
                                                    {{ old('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                                    {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <!-- Selección Automática del Empleado para Empleados -->
                            @if (Auth::user()->isEmpleado())
                                <div class="form-group row mb-3">
                                    <label for="empleado_nombre"
                                        class="col-md-4 col-form-label text-md-right">Empleado</label>
                                    <div class="col-md-6">
                                        <input type="text" name="empleado_nombre" class="form-control"
                                            value="{{ Auth::user()->empleado->nombre1 }} {{ Auth::user()->empleado->apellido1 }}"
                                            readonly>
                                        <input type="hidden" name="empleado_id" value="{{ Auth::user()->empleado->id }}">
                                    </div>
                                </div>
                            @endif


                            <!-- Descripción -->
                            <div class="form-group row mb-3">
                                <label for="descripcion" class="col-md-4 col-form-label text-md-right">Descripción <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <textarea name="descripcion" class="form-control" placeholder="Describe la actividad">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            <!-- Código OSTicket -->
                            <div class="form-group row mb-3">
                                <label for="codigo_osticket" class="col-md-4 col-form-label text-md-right">Código
                                    Osticket</label>
                                <div class="col-md-6">
                                    <input type="text" name="codigo_osticket" class="form-control"
                                        value="{{ old('codigo_osticket') }}">
                                </div>
                            </div>

                            <!-- Semanal o Diario -->
                            <div class="form-group row mb-3">
                                <label for="semanal_diaria" class="col-md-4 col-form-label text-md-right">Frecuencia
                                    <span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <select name="semanal_diaria" class="form-select" required>
                                        <option value="SEMANAL" {{ old('semanal_diaria') == 'SEMANAL' ? 'selected' : '' }}>
                                            Semanal</option>
                                        <option value="DIARIO" {{ old('semanal_diaria') == 'DIARIO' ? 'selected' : '' }}>
                                            Diario</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fecha de Inicio-->
                            <div class="form-group row mb-2">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-md-right">Fecha de
                                    Inicio</label>
                                <div class="col-md-6">
                                    <input type="date" name="fecha_inicio" class="form-control"
                                        value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}" readonly>
                                </div>
                            </div>

                            <!-- Avance -->
                            <div class="form-group row mb-2">
                                <label for="avance" class="col-md-4 col-form-label text-md-right">Avance
                                    (%)<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="number" name="avance" id="avance" class="form-control" value="0"
                                        readonly>
                                </div>
                            </div>

                            <!-- Observaciones-->
                            <div class="form-group row mb-2">
                                <label for="observaciones"
                                    class="col-md-4 col-form-label text-md-right">Observaciones</label>
                                <div class="col-md-6">
                                    <textarea name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>

                            <!-- Estado-->
                            <div class="form-group row mb-2">
                                <label for="estado" class="col-md-4 col-form-label text-md-right">Estado<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="text" name="estado" id="estado" class="form-control"
                                        value="PENDIENTE" readonly>
                                </div>
                            </div>

                            <!-- Tiempo Estimado-->
                            <div class="form-group row mb-2">
                                <label for="tiempo_estimado" class="col-md-4 col-form-label text-md-right">Tiempo
                                    Estimado (minutos)<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <input type="number" name="tiempo_estimado" class="form-control"
                                        value="{{ old('tiempo_estimado') }}" min="0" required>
                                </div>
                            </div>

                            <!-- Fecha de Fin-->
                            <div class="form-group row mb-2">
                                <label for="fecha_fin" class="col-md-4 col-form-label text-md-right">Fecha de
                                    Fin</label>
                                <div class="col-md-6">
                                    <input type="date" name="fecha_fin" class="form-control"
                                        value="{{ old('fecha_fin', now()->format('Y-m-d')) }}" readonly>
                                </div>
                            </div>

                            <!-- Repetitivo-->
                            <div class="form-group row mb-2">
                                <label for="repetitivo" class="col-md-4 col-form-label text-md-right">Repetitivo<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="repetitivo" class="form-select" required>
                                        <option value="1" {{ old('repetitivo') == '1' ? 'selected' : '' }}>Sí
                                        </option>
                                        <option value="0" {{ old('repetitivo') == '0' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Prioridad -->
                            <div class="form-group row mb-2">
                                <label for="prioridad" class="col-md-4 col-form-label text-md-right">Prioridad<span
                                        class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="prioridad" id="prioridad" class="form-select" required>
                                        <option value="ALTA" {{ old('prioridad') == 'ALTA' ? 'selected' : '' }}>Alta
                                        </option>
                                        <option value="MEDIA" {{ old('prioridad') == 'MEDIA' ? 'selected' : '' }}>
                                            Media
                                        </option>
                                        <option value="BAJA" {{ old('prioridad') == 'BAJA' ? 'selected' : '' }}>Baja
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @if (Auth::user()->isAdmin())
                                <!-- Información del Departamento, Cargo y Supervisor (solo para mostrar) -->
                                <div class="form-group row mb-2">
                                    <label for="departamento"
                                        class="col-md-4 col-form-label text-md-right">Departamento</label>
                                    <div class="col-md-6">
                                        <input type="text" id="departamento" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                    <label for="cargo" class="col-md-4 col-form-label text-md-right">Cargo</label>
                                    <div class="col-md-6">
                                        <input type="text" id="cargo" class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row mb-2">
                                    
                                
                                    <!-- Inputs ocultos para enviar los IDs reales al servidor -->
                                    <input type="hidden" name="departamento_id" id="departamento_id">
                                    <input type="hidden" name="cargo_id" id="cargo_id">
                                    <input type="hidden" name="supervisor_id" id="supervisor_id">
                                </div>
                            @endif

                            <!-- Se llene automatico el campo de de departamento al que corresponde al empleado -->
                            @if (Auth::user()->isEmpleado())
                                <div class="form-group
                                    row mb-2">
                                    <label for="departamento_id"
                                        class="col-md-4 col-form-label text-md-right">Departamento</label>
                                    <div class="col-md-6">
                                        <!-- Campo oculto para enviar el ID del departamento -->
                                        <input type="hidden" name="departamento_id"
                                            value="{{ Auth::user()->empleado->departamento->id }}">
                                        <!-- Campo visible que muestra el nombre del departamento solo como lectura -->
                                        <input type="text" class="form-control"
                                            value="{{ Auth::user()->empleado->departamento->nombre }}" readonly>
                                    </div>
                                </div>

                                <!-- Se llene automatico el campo de cargo al que corresponde al empleado -->
                                <div class="form-group row mb-2">
                                    <label for="cargo_id" class="col-md-4 col-form-label text-md-right">Cargo</label>

                                    <div class="col-md-6">
                                        <!-- Campo oculto para enviar el ID del cargo -->
                                        <input type="hidden" name="cargo_id"
                                            value="{{ Auth::user()->empleado->cargo->id }}">
                                        <!-- Campo visible que muestra el nombre del cargo solo como lectura -->
                                        <input type="text" class="form-control"
                                            value="{{ Auth::user()->empleado->cargo->nombre_cargo }}" readonly>
                                    </div>
                                </div>
                            @endif


                            <!-- Tipo de Error -->
                            <div class="form-group row mb-2">
                                <label for="error" class="col-md-4 col-form-label text-md-right">Tipo de
                                    Error<span class="text-danger"> *</span></label>
                                <div class="col-md-6">
                                    <select name="error" class="form-select" required>
                                        <option value="CLIENTE" {{ old('error') == 'CLIENTE' ? 'selected' : '' }}>
                                            Cliente
                                        </option>
                                        <option value="SOFTWARE" {{ old('error') == 'SOFTWARE' ? 'selected' : '' }}>
                                            Software</option>
                                        <option value="MEJORA ERROR"
                                            {{ old('error') == 'MEJORA ERROR' ? 'selected' : '' }}>Mejora
                                            Error
                                        </option>
                                        <option value="DESARROLLO" {{ old('error') == 'DESARROLLO' ? 'selected' : '' }}>
                                            Desarrollo
                                        </option>

                                        <option value="OTRO" {{ old('error') == 'OTRO' ? 'selected' : '' }}>
                                            Otros
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <!-- Botones -->
                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> Guardar
                                    </button>
                                    <a href="{{ route('actividades.indexActividades') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Volver
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateEmployeeInfo() {
    const empleadoSelect = document.getElementById('empleado_id');
    const selectedOption = empleadoSelect.options[empleadoSelect.selectedIndex];

    // Actualizar datos generales
    document.getElementById('departamento').value = selectedOption.getAttribute('data-departamento') || '';
    document.getElementById('departamento_id').value = selectedOption.getAttribute('data-departamento-id') || '';
    document.getElementById('cargo').value = selectedOption.getAttribute('data-cargo') || '';
    document.getElementById('cargo_id').value = selectedOption.getAttribute('data-cargo-id') || '';

}
    </script>
@endsection
