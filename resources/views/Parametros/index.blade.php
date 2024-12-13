@extends('layouts.app')

@section('content')
    <div class="container mt-7">
        <h1 class="text-center mb-4">Parametros</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <a href="{{ route('parametros.create') }}" class="btn btn-primary">Añadir Parametro</a>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre del Parámetro</th>
                    <th>Departamento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($parametros as $parametro)
                    <tr>
                        <td>{{ $parametro->id }}</td>
                        <td>{{ $parametro->nombre }}</td>
                        <td>
                            {{ $parametro->departamento_id ? $parametro->departamento_id : 'Sin departamento' }}
                        </td>
                        <td>
                            <a href="{{ route('parametros.edit', $parametro) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('parametros.destroy', $parametro) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection