@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">Daily Scrum</h1>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('daily.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Registro Diario
                    </a>

                    <form action="{{ route('daily.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por empleado"
                                value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive mt-4">
                    <table id="daily-scrum-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Empleado</th>
                                <th>Ayer</th>
                                <th>Hoy</th>
                                <th>Dificultades</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailies as $daily)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($daily->fecha)->format('d-m-Y') }}</td>
                                    <!-- Fecha formateada -->
                                    <td>{{ $daily->empleado->nombre1 }} {{ $daily->empleado->apellido1 }}</td>
                                    <td>{{ $daily->ayer }}</td>
                                    <td>{{ $daily->hoy }}</td>
                                    <td>{{ $daily->dificultades }}</td>
                                    <td>
                                        @if (Auth::user()->isAdmin())
                                            <a href="{{ route('daily.edit', $daily) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>

                                            <a href="{{ route('daily.show', $daily) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye
                                            "></i> Ver
                                            </a>

                                            <form action="{{ route('daily.destroy', $daily->id) }}" method="POST"
                                                class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash fa-md"></i> Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('daily.show', $daily) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye
                                            "></i> Ver
                                            </a>

                                            <form action="{{ route('daily.destroy', $daily->id) }}" method="POST"
                                                class="d-inline form-delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash fa-md"></i> Eliminar
                                                </button>
                                            </form>
                                        @endif


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Inicializa DataTables para la tabla de Daily Scrum
            $('#daily-scrum-table').DataTable({
                responsive: true,
                pageLength: 10, // Número de filas por página
                lengthMenu: [5, 10, 25, 50], // Opciones de paginación
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    paginate: {
                        first: "Primera",
                        last: "Última",
                        next: "Siguiente",
                        previous: "Anterior"
                    }
                },
                // Ordenar por fecha de forma descendente por defecto
                order: [
                    [0, 'desc'] // Asumiendo que la primera columna es la fecha
                ]
            });



            // SweetAlert para confirmación de eliminación
            document.querySelectorAll('.form-delete').forEach((form) => {
                form.addEventListener('submit', function(e) {
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
                            form.submit();
                        }
                    });
                });
            });

        });
    </script>
@endsection
