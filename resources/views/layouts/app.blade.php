<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Webcoopec System LTDA.</title>

    <!-- CSS de FixedColumns -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
    <!-- JS de FixedColumns -->
    <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js">
    </script>


    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- DataTable Styles -->

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- En la sección <head> de tu layout o directamente en la vista -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="http://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"></script>


    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        html,
        body {
            height: 100%;
            margin 0;
            font-family: 'Nunito', sans-serif;

        }

        #app {
            flex: 1;
            display: flex;
        }

        .sidebar {
            background-color: #343a40;
            color: #fff;
            transition: width 0.3s ease;
            width: 250px;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #495057;
        }

        .sidebar-header img {
            margin-right: 15px;
        }

        .sidebar-header span {
            font-size: 22px;
            font-weight: bold;
        }

        .sidebar.collapsed .sidebar-header span {
            display: none;
        }

        .nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }


        .nav-link {
            color: #fff;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-link:hover {
            background-color: #495057;
            border-radius: 4px;
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 18px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0 auto;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        /* .main-content {
            flex: 1;
            width: 100%;
            height: 100%;

            padding: 0;
            margin: 0;
        } */

        .main-content .container {
            flex: 1;
            width: 100%;
            height: 100%;
            /* Asegura que el contenedor hijo ocupe todo el espacio dentro de .main-content */

            margin: 0;
            padding: 5px;
        }


        .navbar {
            background-color: #0d6efd;
            width: 100%;
            position: fixed top: 0;
            z-index: 1000;
        }
    </style>
</head>

<body>

    <div id="app">
        <!-- Sidebar -->
        <div class="sidebar collapsed" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/icono-wp.png') }}" alt="Icono" style="height: 50px;">
                Webcoopec System LTDA.
            </div>

            <ul class="nav flex-column">
                <!-- Home Link -->
                <li class="nav-item">
                    <a class="nav-link active {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}" title="Inicio">
                        <i class="fas fa-home"></i> <span>Inicio</span>
                    </a>
                </li>

                <!-- Links for Admin -->
                @if (Auth::check() && Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('empleados.indexEmpleados', 'cargos.index', 'departamentos.index') ? 'active' : '' }}"
                            href="#" id="navbarRRHH" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false" title="RRHH">
                            <i class="fas fa-users"></i> <span>RRHH</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarRRHH">
                            <li><a class="dropdown-item {{ request()->routeIs('empleados.indexEmpleados') ? 'active' : '' }}"
                                    href="{{ route('empleados.indexEmpleados') }}" title="Empleados"><i
                                        class="fas fa-users"></i> Empleados</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('cargos.index') ? 'active' : '' }}"
                                    href="{{ route('cargos.index') }}" title="Cargos"><i class="fas fa-briefcase"></i>
                                    Cargos</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('departamentos.index') ? 'active' : '' }}"
                                    href="{{ route('departamentos.index') }}" title="Departamentos"><i
                                        class="fas fa-building"></i> Departamentos</a></li>
                            <li >
                                <a  class="dropdown-item {{ request()->routeIs('supervisores.index') ? 'active' : '' }}"
                                    href="{{ route('supervisores.index') }}" title="Supervisores">
                                    <i class="fas fa-user-shield"></i> <span>Supervisores</span>
                                </a>
                            </li>
                            <li >
                                <a  class="dropdown-item {{ request()->routeIs('cargos.index') ? 'active' : '' }}"
                                    href="{{ route('cargos.index') }}" title="Cargos">
                                    <i class="fas fa-briefcase"></i> <span>Cargos</span>
                                </a>
                            </li>
                            <li>
                                <a  class="dropdown-item {{ request()->routeIs('rubros.index') ? 'active' : '' }}"
                                    href="{{ route('rubros.index') }}" title="Rubros">
                                    <i class="fas fa-money-bill-alt"></i> <span>Rubros</span>
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('actividades.indexActividades') ? 'active' : '' }}"
                            href="{{ route('actividades.indexActividades') }}" title="Actividades">
                            <i class="fas fa-tasks"></i> <span>Actividades</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('daily.index') ? 'active' : '' }}"
                            href="{{ route('daily.index') }}" title="Daily Scrum">
                            <i class="fas fa-calendar-alt"></i> <span>Daily Scrum</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('clientes.index') ? 'active' : '' }}"
                            href="{{ route('clientes.index') }}" title="Clientes">
                            <i class="fas fa-users"></i> <span>Clientes</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('productos.index') ? 'active' : '' }}"
                            href="{{ route('productos.index') }}" title="Productos">
                            <i class="fas fa-cogs"></i> <span>Productos</span>
                        </a>
                    </li>
                   
                   
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('roles_pago.index') ? 'active' : '' }}"
                            href="{{ route('roles_pago.index') }}" title="Roles de Pago">
                            <i class="fas fa-credit-card"></i> <span>Roles de Pago</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('parametros.index') ? 'active' : '' }}"
                            href="{{ route('parametros.index') }}" title="Parámetros">
                            <i class="fas fa-cogs"></i> <span>Parámetros</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('matriz_Cumplimientos.index') ? 'active' : '' }}"
                            href="{{ route('matriz_cumplimientos.index') }}" title="Matriz de Cumplimiento">
                            <i class="fas fa-check-square"></i> <span>Matriz de Cumplimiento</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('imagen.index') ? 'active' : '' }}"
                            href="{{ route('imagen.index') }}" title="Anuncios">
                            <i class="fas fa-image"></i> <span>Anuncios</span>
                        </a>
                    </li>

                    
                @endif

                <!-- Links for Empleado -->
                @if (Auth::check() && Auth::user()->isEmpleado())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('actividades.indexActividades') ? 'active' : '' }}"
                            href="{{ route('actividades.indexActividades') }}" title="Actividades">
                            <i class="fas fa-tasks"></i> <span>Actividades</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('daily.index') ? 'active' : '' }}"
                            href="{{ route('daily.index') }}" title="Daily Scrum">
                            <i class="fas fa-calendar-alt"></i> <span>Daily Scrum</span>
                        </a>
                    </li>
                @endif

                <!-- Links for Supervisor -->
                @if (Auth::check() && Auth::user()->isEmpleado() && Auth::user()->empleado->es_supervisor)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('matriz_Cumplimientos.index') ? 'active' : '' }}"
                            href="{{ route('matriz_cumplimientos.index') }}" title="Matriz de Cumplimiento">
                            <i class="fas fa-check-square"></i> <span>Matriz de Cumplimiento</span>
                        </a>
                    </li>
                @endif
                @if (Auth::check() && Auth::user()->id == 8)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('imagen.index') ? 'active' : '' }}"
                            href="{{ route('imagen.index') }}" title="Anuncios">
                            <i class="fas fa-image"></i> <span>Anuncios</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <nav class="navbar navbar-expand-md navbar-light shadow-sm">

                <button class="btn btn-primary sidebar-toggler" id="sidebarToggler">
                    <i class="fa fa-bars"></i>
                </button>

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

            </nav>

            <main class="py-4">
                <div class="container ce">
                    @yield('content')
                </div>
            </main>

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
            const sidebar = document.getElementById('sidebar');
            const sidebarToggler = document.getElementById('sidebarToggler');

            sidebarToggler.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        });
    </script>
</body>

</html>
