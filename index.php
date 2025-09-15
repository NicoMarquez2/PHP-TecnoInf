<?php
session_start();
require 'bd.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurante tecno-inf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

    <!-- Banner -->
    <section id="section-banner" class="container-fluid p-0">
        <div class="banner" 
            style="position:relative; 
                   background:url('images/bg-asado.JPG') center/cover no-repeat; 
                   height: 700px;
                   background-color: rgba(0,0,0,0.65);
                   background-blend-mode: darken;">
            <div class="banner-text" 
                style="position:absolute; 
                       top: 50%; 
                       left:50%; 
                       transform: translate(-50%, -50%); 
                       text-align:center; 
                       color:white;">
                <h1 class="display-3 fw-bold">Restaurante tecno-inf</h1>
                <a href="#menu" class="btn btn-lg btn-primary shadow mt-3">Ver Menú</a> 
            </div>
        </div>
    </section>

    <!-- Bienvenida (full width) -->
    <section class="bg-dark text-white py-5 text-center">
        <div class="container">
            <h1 class="mb-4">Bienvenido al Restaurante tecno-inf</h1>
            <?php if(isset($_SESSION['usuario'])): ?>
                <p class="lead">Has iniciado sesión como 
                    <strong><?php echo htmlspecialchars($_SESSION['tipo']); ?></strong>: 
                    <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
                </p>
            <?php else: ?>
                <p class="lead">Por favor inicia sesión o regístrate para acceder a todas las funciones.</p>
            <?php endif; ?>
            <br>
            <p>En Restaurante Tecno-Inf combinamos la tradición culinaria con un toque moderno para ofrecerte una experiencia única.
                Nuestros platos están preparados con ingredientes frescos y de la más alta calidad, pensados para deleitar todos los paladares.
                Ya sea que nos visites en familia, con amigos o en pareja, queremos que disfrutes de un ambiente acogedor y un servicio cercano que te haga sentir como en casa.</p>
        </div>
    </section>

    <!-- Menú -->
<div id="menu" class="container my-5"> 
    <h1 class="text-center mb-5">Nuestro menú</h1>

    <div class="row g-4">
        <?php
            $stmt = $conexion->query("SELECT * FROM menu");
            $platos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($platos as $plato) {
                echo '<div class="col-md-4">';
                echo '  <div class="card h-100 shadow border-0 rounded-3">';
                echo '      <img src="' . htmlspecialchars($plato['foto']) . '" 
                                  class="card-img-top" 
                                  alt="' . htmlspecialchars($plato['nombre']) . '" 
                                  style="height: 220px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">';
                echo '      <div class="card-body d-flex flex-column">';
                echo '          <h5 class="card-title fw-bold">' . htmlspecialchars($plato['nombre']) . '</h5>';
                echo '          <p class="card-text">' . htmlspecialchars($plato['descripcion']) . '</p>';
                echo '          <p class="card-text mt-auto"><strong>Precio: $' . number_format($plato['precio'], 2) . '</strong></p>';
                
                // Mostrar botón solo si es cliente
                if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cliente') {
                    echo '<a href="#" class="btn btn-dark mt-3">Agregar al carrito</a>';
                }

                echo '      </div>';
                echo '  </div>';
                echo '</div>';
            }
        ?>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script> 
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
</body>
</html>
