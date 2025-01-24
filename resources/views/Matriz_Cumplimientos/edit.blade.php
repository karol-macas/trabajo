@extends('layouts.app')

@section('content')
    <div class="container mt-4" style="max-width: 700px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Editar Matriz de Cumplimientos</h1>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('matriz_cumplimientos.update', $cumplimiento->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Empleado -->
                    <div class="form-group mt-3">
                        <label for="empleado_id">Empleado <span class="text-danger">*</span></label>
                        <select name="empleado_id" id="empleado_id" class="form-control" required>
                            <option value="">Seleccione un Empleado</option>
                            @foreach ($empleados as $empleado)
                                <option value="{{ $empleado->id }}" 
                                    data-cargo-id="{{ optional($empleado->cargo)->id }}" 
                                    data-supervisor-id="{{ optional($empleado->supervisor)->id }}"
                                    data-departamento="{{ optional($empleado->departamento)->nombre }}"
                                    {{ $cumplimiento->empleado_id == $empleado->id ? 'selected' : '' }}>
                                    {{ $empleado->nombre1 . ' ' . $empleado->apellido1 }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cargo -->
                    <div class="form-group mt-3">
                        <label for="cargo_id">Cargo <span class="text-danger">*</span></label>
                        <select name="cargo_id" id="cargo_id" class="form-control" required>
                            <option value="">Seleccione un Cargo</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->id }}" {{ $cumplimiento->cargo_id == $cargo->id ? 'selected' : '' }}>
                                    {{ $cargo->nombre_cargo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Supervisor -->
                    <div class="form-group mt-3">
                        <label for="supervisor_id">Supervisor <span class="text-danger">*</span></label>
                        <select name="supervisor_id" id="supervisor_id" class="form-control" required>
                            <option value="">Seleccione un Supervisor</option>
                            @foreach ($supervisores as $supervisor)
                                <option value="{{ $supervisor->id }}" {{ $cumplimiento->supervisor_id == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->nombre_supervisor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Parámetro -->
                    <div class="form-group mt-3">
                        <label for="parametro_id">Parámetro <span class="text-danger">*</span></label>
                        <select name="parametro_id" id="parametro_id" class="form-control" required>
                            <option value="">Seleccione un Parámetro</option>
                            @foreach ($parametros as $parametro)
                                <option value="{{ $parametro->id }}" {{ $cumplimiento->parametro_id == $parametro->id ? 'selected' : '' }}>
                                    {{ $parametro->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Puntos -->
                    <div class="form-group mt-3">
                        <label for="puntos">Puntos <span class="text-danger">*</span></label>
                        <input type="number" id="puntos" name="puntos" class="form-control" value="{{ old('puntos', $cumplimiento->puntos) }}" min="0.5" step="0.5" required>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">Actualizar Matriz de Cumplimientos</button>
                        <a href="{{ route('matriz_cumplimientos.index') }}" class="btn btn-link">Regresar al listado de Matrices de Cumplimientos</a>
                    </div>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const empleadoSelect = document.getElementById('empleado_id');
                        const cargoSelect = document.getElementById('cargo_id');
                        const supervisorSelect = document.getElementById('supervisor_id');
                       
                        const puntosInput = document.getElementById('puntos');

                        // Prefill fields with current values
                        empleadoSelect.addEventListener('change', function () {
                            const selectedOption = empleadoSelect.options[empleadoSelect.selectedIndex];
                            const cargoId = selectedOption.getAttribute('data-cargo-id');
                            const supervisorId = selectedOption.getAttribute('data-supervisor-id');
                          

                            // Set Cargo
                            if (cargoId) {
                                cargoSelect.value = cargoId;
                            } else {
                                cargoSelect.value = '';
                            }

                            // Set Supervisor
                            if (supervisorId) {
                                supervisorSelect.value = supervisorId;
                            } else {
                                supervisorSelect.value = '';
                            }

                            // Set Departamento
                            departamentoInput.value = departamento ? departamento : '';
                        });

                        // Validación para puntos
                       puntosInput.addEventListener('input', function () {
    let puntos = parseFloat(puntosInput.value);

    // Asegurarse de que el valor sea un múltiplo de 0.5
    if (puntos % 0.5 !== 0) {
        puntosInput.setCustomValidity('Los puntos deben ser múltiplos de 0.5');
    } else {
        puntosInput.setCustomValidity('');
    }
});
                    });
                </script>

            </div>
        </div>
    </div>
@endsection


