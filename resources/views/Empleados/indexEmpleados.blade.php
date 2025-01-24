@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-8">Listado de Empleados</h1>

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

        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('empleados.create') }}" class="btn btn-primary mb-3">Nuevo Empleado</a>
        </div>

        <div class="table-responsive">
            <table id="empleados-table" class="table table-hover table-bordered w-100 table-sm">
                <thead class="thead-dark text-center">
                    <tr>
                  
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>cedula</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Celular</th>
                        <th>Correo</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Supervisor</th>
                        <th>Fecha de Contratación</th>
                        <th>Tipo de Jornada</th>
                        <th>Curriculum</th>
                        <th>Contrato</th>
                        <th>Contrato de Confidencialidad</th>
                        <th>Contrato de Consentimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empleados as $empleado)
                        <tr>
                            
                            <td>{{ $empleado->nombre1 . ' ' . $empleado->nombre2 }}</td>
                            <td>{{ $empleado->apellido1 . ' ' . $empleado->apellido2 }}</td>
                            <td>{{ $empleado->cedula }}</td>
                            <td>{{ $empleado->fecha_nacimiento }}</td>
                            <td>{{ $empleado->celular }}</td>
                            <td>{{ $empleado->correo_institucional }}</td>
                            <td>{{ optional($empleado->departamento)->nombre ?? 'N/A' }}</td>
                            <td>{{ optional($empleado->cargo)->nombre_cargo ?? 'N/A' }}</td>
                            <td>
                                @if ($empleado->es_supervisor && !$empleado->supervisor_id)
                                    Supervisor Superior
                                @elseif ($empleado->es_supervisor)
                                    Supervisor
                                @elseif ($empleado->supervisor_id)
                                    {{ $empleado->supervisor->nombre_supervisor ?? 'N/A' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $empleado->fecha_contratacion ? \Carbon\Carbon::parse($empleado->fecha_contratacion)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $empleado->jornada_laboral }}</td>
                            <td>
                                @if($empleado->curriculum)
                                    <a href="{{ asset($empleado->curriculum) }}" target="_blank">Ver Currículum</a>
                                @else
                                    <span class="text-danger">No tiene curriculum</span>
                                @endif
                            </td>
                            <td>
                                @if($empleado->contrato)
                                    <a href="{{ asset($empleado->contrato) }}" target="_blank">Ver Contrato</a>
                                @else
                                    <span class="text-danger">No tiene contrato</span>
                                @endif
                            </td>
                            <td>
                                @if($empleado->contrato_confidencialidad)
                                    <a href="{{ asset($empleado->contrato_confidencialidad) }}" target="_blank">Ver Contrato de Confidencialidad</a>
                                @else
                                    <span class="text-danger">No tiene contrato de confidencialidad</span>
                                @endif
                            </td>
                            <td>
                                @if($empleado->contrato_consentimiento)
                                    <a href="{{ asset($empleado->contrato_consentimiento) }}" target="_blank">Ver Contrato de Consentimiento</a>
                                @else
                                    <span class="text-danger">No tiene contrato de consentimiento</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('empleados.show', $empleado->id) }}" class="btn btn-info btn-sm" title="Ver"><i class="fas fa-eye fa-md"></i></a>
                                <a href="{{ route('empleados.edit', $empleado->id) }}" class="btn btn-warning btn-sm" title="Editar"><i class="fas fa-edit fa-md"></i></a>
                                <form action="{{ route('empleados.destroy', $empleado->id) }}" method="POST" class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" title="Eliminar">
                                        <i class="fas fa-trash fa-md"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- SweetAlert y DataTables Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#empleados-table').DataTable({
                responsive: true,
                pageLength: 15,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ empleados",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ empleados",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                order: [[0, 'desc']]
            });

            // SweetAlert para confirmación de eliminación
            document.querySelectorAll('.form-delete').forEach(form => {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    })
                });
            });
        });
    </script>



@endsection

