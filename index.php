<?php
session_start();
require 'bd.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Restaurante</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar" aria-controls="navBar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navBar">
                <!-- Navbar izquierdo -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php if(isset($_SESSION['tipo'])): ?>
                        <?php if($_SESSION['tipo'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="agregar_menu.php">Agregar Menú</a></li>
                            <li class="nav-item"><a class="nav-link" href="modificar_menu.php">Modificar Menú</a></li>
                            <li class="nav-item"><a class="nav-link" href="eliminar_menu.php">Eliminar Menú</a></li>
                        <?php else: ?>
                            <!-- Opciones principales del cliente -->
                            <li class="nav-item"><a class="nav-link" href="menu.php">Ver Menú</a></li>
                            <!-- Opcionales -->
                            <li class="nav-item"><a class="nav-link" href="favoritos.php">Favoritos</a></li>
                            <li class="nav-item"><a class="nav-link" href="carrito.php">Carrito</a></li>
                            <li class="nav-item"><a class="nav-link" href="historial.php">Historial</a></li>
                            <li class="nav-item"><a class="nav-link" href="ordenar.php">Ordenar Menú</a></li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>

                <!-- Navbar derecho -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if(isset($_SESSION['usuario'])): ?>
                        <li class="nav-item d-flex align-items-center me-2">
                            <span class="text-white">
                                <?php echo $_SESSION['usuario'] . " (" . $_SESSION['tipo'] . ")"; ?>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-person-plus"></i> Registrarse</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5">
        <h1>Bienvenido al Restaurante</h1>
        <?php if(isset($_SESSION['usuario'])): ?>
            <p>Has iniciado sesión como <strong><?php echo $_SESSION['tipo']; ?></strong>: <strong><?php echo $_SESSION['usuario']; ?></strong></p>
        <?php else: ?>
            <p>Por favor inicia sesión o regístrate para acceder a todas las funciones.</p>
        <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>