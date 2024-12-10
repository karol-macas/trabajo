@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center mb-4">Matriz de Cumplimientos</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('matriz_cumplimientos.create') }}" class="btn btn-primary">Añadir Cumplimiento</a>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Parámetro</th>
                    <th>Puntos</th>
                    <th>Empleado</th>
                    <th>Cargo</th>
                    <th>Supervisor</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->id  }}</td>
                        <td>{{ $empleado->parametro->nombre ?? 'No asignado' }}</td>
                        <td>{{ $empleado->puntos }}</td>
                        <td>{{ $empleado->nombre1  }}</td>
                        <td>{{ $empleado->cargo->nombre_cargo ?? 'No asignado' }}</td>
                        <td>{{ $empleado->supervisor->nombre_supervisor ?? 'Sin supervisor' }}</td>
                        <td>
                            <a href="{{ route('matriz_cumplimientos.edit', $empleado) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('matriz_cumplimientos.destroy', $empleado) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
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
@endsection
