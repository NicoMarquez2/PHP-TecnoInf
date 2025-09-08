<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/index.php">Restaurante tecno-inf</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar" aria-controls="navBar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navBar">
            <!-- Navbar izquierdo -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if(isset($_SESSION['tipo'])): ?>
                    <?php if($_SESSION['tipo'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/agregar_menu.php">Agregar Menú</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/modificar_menu.php">Modificar Menú</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/eliminar_menu.php">Eliminar Menú</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/crear_admin.php">Registrar Admin</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/cliente/favoritos.php">Favoritos</a></li>
                        <li class="nav-item"><a class="nav-link" href="/cliente/carrito.php">Carrito</a></li>
                        <li class="nav-item"><a class="nav-link" href="/cliente/historial.php">Historial</a></li>
                        <li class="nav-item"><a class="nav-link" href="/cliente/ordenar.php">Ordenar Menú</a></li>
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
                        <a class="nav-link" href="/logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/register.php"><i class="bi bi-person-plus"></i> Registrarse</a></li>
                    <li class="nav-item"><a class="nav-link" href="/login.php"><i class="bi bi-box-arrow-in-right"></i> Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>