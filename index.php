<?php
session_start();
require 'bd.php';
?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

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