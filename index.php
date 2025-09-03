<?php
session_start();
require 'bd.php';
?>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

    <section id="section-banner" class="container-fluid p-0">
    <div class="banner" style="position:relative; background:url('images/bg-asado.JPG') center/cover no-repeat; height: 700px;">
        <div class="banner-text" style="position:absolute; top: 50%; left:50%; transform: translate(-50%, -50%); text-align:center; color:white";>
            <h1>Restaurante tecno-inf</h1>
            <a href="menu.php" class="btn btn-primary">Ver Menu</a> 
        </div>
    </div>
    </section>

    <section id="id" class="container mt-4 text-center">

    <div class="jumbotron bg-dark text-white">

        <br/>
            <h1>Bienvenido al Restaurante tecno-inf</h1>
        <?php if(isset($_SESSION['usuario'])): ?>
            <p>Has iniciado sesión como <strong><?php echo $_SESSION['tipo']; ?></strong>: <strong><?php echo $_SESSION['usuario']; ?></strong></p>
        <?php else: ?>
            <p>Por favor inicia sesión o regístrate para acceder a todas las funciones.</p>
        <?php endif; ?>
        <br/>

    </div>

    </section>



    <main class="container mt-5">
      
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script> 
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
</body>
</html>