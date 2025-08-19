<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CALCUPESO - Calculadora de Ganancias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">PesoCalcu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/">➕ Nueva comida</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/ingredientes/create">🧂 Crear ingrediente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/stock">📦 Ver stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/comidas/disponibles">📋 Comidas disponibles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/comidas/disponibles_con_stock">✅ Con stock</a>
                        
                    </li>

                         <li class="nav-item">
                        <a href="{{ route('tienda.cliente') }}" class="nav-link">➕Ir a la tienda</a>
                        
                    </li>

                    </li>    
                        <a class="nav-link" href='/bebidas'>➕Agregar Bebida</a>
                        <a class="nav-link" href='/bebidas/stock'>📦Ver Stock de Bebidas</a>
                    </li> 
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="container mt-4">
        @yield('content')
    </main>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
