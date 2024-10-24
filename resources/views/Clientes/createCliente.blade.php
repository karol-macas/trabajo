@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="mb-0"><i class="fas fa-user-plus"></i> Registrar Nuevo Cliente</h2>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger m-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                        @csrf

                        <!-- Campo Producto -->
                        <div class="form-group mb-3">
                            <label for="productos">Selecciona Productos</label>

                            <select name="productos[]" id="productos" class="form-control" multiple required>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campo Nombre -->
                        <div class="form-group mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                        </div>

                        <!-- Campo Dirección -->
                        <div class="form-group mb-3">
                            <label for="direccion">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}"
                                required>
                        </div>

                        <!-- Campo Teléfono -->
                        <div class="form-group mb-3">
                            <label for="telefono">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}"
                                required>
                        </div>

                        <!-- Campo Email -->
                        <div class="form-group mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <!-- Campo Contacto -->
                        <div class="form-group mb-3">
                            <label for="contacto">Contacto</label>
                            <input type="text" name="contacto" class="form-control" value="{{ old('contacto') }}"
                                required>
                        </div>

                        <!-- Contrato de Implementacion -->
                        <div class="form-group mb-3">
                            <label for="contrato_implementacion">Contrato de Implementación</label>
                            <input type="file" name="contrato_implementacion" class="form-control">
                        </div>

                        <!-- Convenio de Datos -->
                        <div class="form-group  mb-3">
                            <label for="convenio_datos">Convenio de Datos</label>
                            <input type="file" name="convenio_datos" class="form-control">
                        </div>

                        <!-- Campo Documentos Otros -->
                        <div class="form-group mb-3">
                            <label for="documento_otros">Documentos Otros</label>
                            <input type="file" name="documento_otros[]" class="form-control" multiple>
                        </div>

                        <!-- Campo Precio -->
                        <div class="form-group mb-3">
                            <label for="precio">Precio</label>
                            <input type="number" name="precio" class="form-control" value="{{ old('precio') }}"
                                min="0" required>
                        </div>

                        <!-- Campo Estado -->
                        <div class="form-group mb-3">
                            <label for="estado">Estado</label>
                            <select name="estado" class="form-control" required>
                                <option value="ACTIVO" {{ old('estado') == 'ACTIVO' ? 'selected' : '' }}>Activo</option>
                                <option value="INACTIVO" {{ old('estado') == 'INACTIVO' ? 'selected' : '' }}>Inactivo
                                </option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                            <a href="{{ route('clientes.index') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#productos').select2({
                placeholder: "Selecciona productos",
                allowClear: true
            });
        });
    </script>

@endsection
