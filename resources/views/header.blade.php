<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calculadora de Ganancias - Local de Comida</title>
    <!-- Podés agregar CSS aquí, por ejemplo Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <header class="bg-primary text-white p-3 mb-4">
        <div class="container">
            <h1>Calculadora de Ganancias - Local de comida por peso - Pesonero</h1>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <!-- Opcional: JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
