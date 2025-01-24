@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Dashboard</h2>
                    </div>
                    <div class="card-body ">
                        <div class="row justify-content-center text-center">
                            @if (Auth::user()->isAdmin())
                                @foreach ([
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'rrhh.png', 'title' => 'RRHH'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'activos.png', 'title' => 'Activos'],
                                    ['route' => 'actividades.indexActividades', 'img' => 'actividades.png', 'title' => 'Actividades'],
                                    ['route' => 'clientes.index', 'img' => 'cooperativas.png', 'title' => 'Clientes'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'clientes.png', 'title' => 'Empleados'],
                                    ['route' => 'departamentos.index', 'img' => 'departamentos.png', 'title' => 'Departamentos'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'cobros.png', 'title' => 'Cobros'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'mensajeria.png', 'title' => 'Mensajería'],
                                    ['route' => 'productos.index', 'img' => 'productos.png', 'title' => 'Productos'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'seguridad.png', 'title' => 'Seguridades'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'ventas.png', 'title' => 'Ventas'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'rrhh.png', 'title' => 'Usuarios'],
                                    ['route' => 'empleados.indexEmpleados', 'img' => 'inteligencia-de-negocios.png', 'title' => 'Inteligencia de Negocios'],
                                    ['route' => 'supervisores.index', 'img' => 'supervisor.png', 'title' => 'Supervisores'],
                                    ['route' => 'cargos.index', 'img' => 'cargos.png', 'title' => 'Cargos'],
                                    ['route' => 'rubros.index', 'img' => 'cobros.png', 'title' => 'Rubros'],
                                    ['route' => 'roles_pago.index', 'img' => 'rol-de-pagos.png', 'title' => 'Roles de Pago'],
                                    ['route' => 'daily.index', 'img' => 'scrum.png', 'title' => 'Daily Scrum'],
                                    ['route' => 'parametros.index', 'img' => 'parametros.png', 'title' => 'Parámetros'],
                                    ['route' => 'matriz_cumplimientos.index', 'img' => 'ventas.png', 'title' => 'Matriz de Cumplimientos'],
                                    ['route' => 'imagen.index', 'img' => 'ventas.png', 'title' => 'Anuncio'],
        ] as $item)
                                    <div class="col-md-3 mb-4">
                                        <div class="card h-100 shadow">
                                            <div class="card-body d-flex flex-column align-items-center">
                                                <a href="{{ route($item['route']) }}">
                                                    <img src="{{ asset('images/' . $item['img']) }}"
                                                        alt="{{ $item['title'] }}" class="img-fluid mb-2"
                                                        style="width: 60px; height: 60px;">
                                                    <h5 class="card-title">{{ $item['title'] }}</h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif (Auth::user()->isEmpleado())

                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow   center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('actividades.indexActividades') }}">
                                                <img src="{{ asset('images/actividades.png') }}" alt="Actividades"
                                                    class="img-fluid mb-2" style="width: 60px; height: 60px;">
                                                <h5 class="card-title">Actividades</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('daily.index') }}">
                                                <img src="{{ asset('images/scrum.png') }}" alt="Daily Scrum"
                                                    class="img-fluid mb-2" style="width: 60px; height: 60px;">
                                                <h5 class="card-title">Daily Scrum</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->isEmpleado() && Auth::user()->empleado->es_supervisor)
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center"> 
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('matriz_cumplimientos.index') }}">
                                                <img src="{{ asset('images/ventas.png') }}" alt="Matriz de Cumplimientos"
                                                    class="img-fluid mb-2" style="width: 60px; height: 60px;">
                                                <h5 class="card-title">Matriz de Cumplimientos</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (Auth::user()->id == 8)
                                <div class="col-md-5 mb-5 center">
                                    <div class="card h-100 shadow center">
                                        <div class="card-body d-flex flex-column align-items-center">
                                            <a href="{{ route('imagen.index') }}">
                                                <img src="{{ asset('images/ventas.png') }}" alt="Imagen"
                                                    class="img-fluid mb-2" style="width: 60px; height: 60px;">
                                                <h5 class="card-title">anuncios</h5>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.3s;
        }


    </style>
@endsection
