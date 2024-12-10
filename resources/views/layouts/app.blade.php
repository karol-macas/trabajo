<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Webcoopec System LTDA.</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- DataTable Styles -->

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- En la sección <head> de tu layout o directamente en la vista -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="http://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"></script>


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            /* Asegura que los márgenes y rellenos no afecten el ancho total */
        }

        body,
        html {
            height: 100%;
            width: 100%;
        }

        .navbar {
            background-color: #0d6efd;
        }

        .navbar-brand,

        .nav-link {
            color: #ffffff;

        }

        .nav-link:hover {
            color: #574242ef;
        }

        /* Estilo para el encabezado de la barra lateral */
        .sidebar-header {
            color: #ffffff;
            /* Color blanco para el texto */
            font-size: 18px;
            /* Tamaño de fuente */
            text-align: center;
            /* Centrar el texto */
            margin-bottom: 20px;
         
        }


        /* Barra lateral */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #353f49;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            overflow-y: auto;
            display: block;
            font-family: 'Arial', sans-serif;

        }

        .sidebar .nav-link {
            color: #ffffff;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #ffffff;
        }



        .sidebar .nav-item .nav-link i {
            margin-right: 10px;
        }

        /* Ajustes del contenido principal */
        .main-content {
            margin-left: 250px;
            /* Para que el contenido no se solape con la barra lateral */
            padding: 20px;
            width: calc(100% - 250px);
            /* Para asegurar que ocupe el resto del espacio */
            height: 100vh;
            overflow: auto;
            /* Para que el contenido se ajuste correctamente */
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                width: 0;
                /* Reducir el ancho en dispositivos móviles */
            }

            .sidebar.active {
                width: 250px;
                /* Mostrar la barra lateral cuando esté activa */
            }

            .main-content {
                margin-left: 0;
                /* El contenido principal ocupa todo el ancho en pantallas pequeñas */
                width: 100%;
                /* Asegura que el contenido se ajuste */
            }

            .navbar-toggler {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="row">
            <!-- Barra lateral -->

            <div class="col-md-3 sidebar" id="sidebar">
                <div class="sidebar-header">
                    <img src="{{ asset('images/icono-wp.png') }}" alt="Icono" style="height: 50px;">
                    Webcoopec System LTDA.
                </div>

                <ul class="nav flex-column">
                    @if (Auth::user()->isAdmin())
                    @foreach ([
                ['route' => 'empleados.indexEmpleados', 'img' => 'rrhh.png', 'title' => 'RRHH'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'activos.png', 'title' => 'Activos'],
                ['route' => 'actividades.indexActividades', 'img' => 'actividades.png', 'title' => 'Actividades', 
                ],

                ['route' => 'clientes.index', 'img' => 'cooperativas.png', 'title' => 'Clientes'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'clientes.png', 'title' => 'Empleados', 
                ],
                ['route' => 'departamentos.index', 'img' => 'departamentos.png', 'title' => 'Departamentos'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'cobros.png', 'title' => 'Cobros'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'mensajeria.png', 'title' => 'Mensajería'],
                ['route' => 'productos.index', 'img' => 'productos.png', 'title' => 'Productos'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'seguridad.png', 'title' => 'Seguridades'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'ventas.png', 'title' => 'Ventas'],
                ['route' => 'empleados.indexEmpleados', 'img' => 'inteligencia-de-negocios.png', 'title' => 'Inteligencia de Negocios'],
                ['route' => 'supervisores.index', 'img' => 'supervisor.png', 'title' => 'Supervisores'],
                ['route' => 'cargos.index', 'img' => 'cargos.png', 'title' => 'Cargos'],
                ['route' => 'rubros.index', 'img' => 'cobros.png', 'title' => 'Rubros'],
                ['route' => 'roles_pago.index', 'img' => 'rol-de-pagos.png', 'title' => 'Roles de Pago'],
                ['route' => 'daily.index', 'img' => 'scrum.png', 'title' => 'Daily Scrum'],
                ['route' => 'parametros.index', 'img' => '', 'title' => 'Parámetros'],
                ['route' => 'matriz_cumplimientos.index', 'img' => 'ventas.png', 'title' => 'Matriz de Cumplimientos'],
    ] as $menuItem)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route($menuItem['route']) }}">
                                <i class="fa fa-fw"><img src="{{ asset('images/' . $menuItem['img']) }}" alt=""
                                        width="20"></i>
                                {{ $menuItem['title'] }}
                            </a>

                            <!-- Submenú (solo si existe) -->
                            @isset($menuItem['submenu'])
                                <ul class="nav flex-column ms-3 submenu" style="display: none;">
                                    @foreach ($menuItem['submenu'] as $submenuItem)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route($submenuItem['route']) }}">
                                                <i class="fa fa-fw"><img src="{{ asset('images/' . $submenuItem['img']) }}"
                                                        alt="" width="20"></i>
                                                {{ $submenuItem['title'] }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endisset
                        </li>
                    @endforeach
                @elseif (Auth::user()->isEmpleado())
                @foreach ([
                ['route' => 'actividades.indexActividades', 'img' => 'actividades.png', 'title' => 'Actividades', 'submenu' => [['route' => 'actividades.indexActividades', 'img' => 'list-actividades.png', 'title' => 'Ver Actividades']]],
                ['route' => 'daily.index', 'img' => 'scrum.png', 'title' => 'Daily Scrum'],
            ] as $menuItem)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route($menuItem['route']) }}">
                            <i class="fa fa-fw"><img src="{{ asset('images/' . $menuItem['img']) }}" alt="" width="20"></i>
                            {{ $menuItem['title'] }}
                        </a>

                        <!-- Submenú (solo si existe) -->
                        @isset($menuItem['submenu'])
                            <ul class="nav flex-column ms-3 submenu" style="display: none;">
                                @foreach ($menuItem['submenu'] as $submenuItem)
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route($submenuItem['route']) }}">
                                            <i class="fa fa-fw"><img src="{{ asset('images/' . $submenuItem['img']) }}"
                                                    alt="" width="20"></i>
                                            {{ $submenuItem['title'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endisset
                    </li>
                @endforeach
            @endif
            @if (Auth::user()->isEmpleado() && Auth::user()->empleado->es_supervisor)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('matriz_cumplimientos.index') }}">
                        <i class="fa fa-fw"><img src="{{ asset('images/ventas.png') }}" alt="" width="20"></i>
                        Matriz de Cumplimientos
                    </a>
                </li>
            @endif

                </ul>
            </div>
        </div>

        <div class="col-md-9 main-content">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <img src="{{ asset('images/icono-wp.png') }}" alt="Icono" style="height: 50px;">
                        Webcoopec System LTDA.
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Registrarse') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Salir</a>
                                        </li>
                                    </ul>
                                </li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="py-4">
                @yield('content')
            </main>
        </div>
    </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
       document.addEventListener('DOMContentLoaded', function() {
    // Seleccionamos todos los elementos que tienen submenú
    const menuItems = document.querySelectorAll('.nav-item');

    menuItems.forEach(function(item) {
        // Evento click en cada elemento del menú
        item.addEventListener('click', function(e) {
            const submenu = this.querySelector('.submenu');

            // Si el elemento tiene submenú, gestionamos la apertura/cierre
            if (submenu) {
                e.preventDefault(); // Detenemos la navegación solo si hay submenú
                submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
});

    </script>
</body>

</html>
