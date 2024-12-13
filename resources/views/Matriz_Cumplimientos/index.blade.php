@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Matriz de Cumplimientos</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->has('error'))
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                    viewBox="0 0 16 16" role="img" aria-label="Warning:" width="24" height="24" fill="currentColor">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                </svg>
                <div>
                    {{ $errors->first('error') }}
                </div>
            </div>
        @endif

        <form action="{{ route('matriz_cumplimientos.index') }}" method="GET" class="mb-4 p-4 shadow bg-light rounded">
            <div class="form-group">
                <label for="empleado_id" class="form-label fs-5">Seleccionar Empleado:</label>
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white">
                        <i class="fas fa-user"></i>
                    </span>
                    <select name="empleado_id" id="empleado_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Todos los empleados --</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}"
                                {{ request('empleado_id') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre1 }} {{ $empleado->apellido1 }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('matriz_cumplimientos.create') }}" class="btn btn-primary">Añadir Cumplimiento</a>
        </div>

        <div class="table-responsive">
            <table id="matriz-table" class="table table-hover table-bordered w-100 table-sm">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Parámetro</th>
                        <th scope="col">Puntos</th>
                        <th scope="col">Empleado</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">Supervisor</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cumplimientos as $cumplimiento)
                        <tr>
                            <td>{{ $cumplimiento->id }}</td>
                            <td>{{ $cumplimiento->parametro->nombre }}</td>
                            <td>{{ $cumplimiento->puntos }}</td>
                            <td>{{ $cumplimiento->empleado_id }}</td>
                            <td>{{ $cumplimiento->cargo_id }}</td>
                            <td>{{ $cumplimiento->supervisor_id }}</td>
                            <td class="text-center">
                                <a href="{{ route('matriz.show', $cumplimiento->id) }}"
                                    class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye fa-md"></i>
                                </a>

                                


                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $empleados->links() }}
            </div>
        </div>
        {{-- SweetAlert script --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- DataTables initialization --}}
        <script>
            $(document).ready(function() {
                $('#actividades-table').DataTable({
                    responsive: true,
                    pageLength: 10, // Número de filas por página
                    lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ actividades",
                        info: "Mostrando _START_ a _END_ de _TOTAL_ actividades",
                        paginate: {
                            first: "Primera",
                            last: "Última",
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    },
                    // Ordenar por ID de forma descendente por defecto
                    order: [
                        [0, 'desc']
                    ]
                });

                // SweetAlert for delete confirmation
                $('.form-delete').submit(function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                        }
                    });
                });
            });


            function confirmUpdateEstado(id) {
                Swal.fire({
                    title: '¿Estás seguro de actualizar el estado?',
                    text: '¡Este cambio será guardado!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Encuentra el formulario asociado y envíalo
                        document.querySelector(`#form-estado-${id}`).submit();
                    }
                });
            }
        </script>
    @endsection
