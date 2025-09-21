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

            <?php
        // Inicializar variable de ordenamiento
        $order_sql = "";
        $ordenSeleccionado = "";

        // Solo para cliente logueado
        if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cliente') {
            if (isset($_GET['orden'])) {
                $ordenSeleccionado = $_GET['orden'];
                switch ($ordenSeleccionado) {
                    case 'precio_asc':
                        $order_sql = "ORDER BY precio ASC";
                        break;
                    case 'precio_desc':
                        $order_sql = "ORDER BY precio DESC";
                        break;
                    case 'nombre_asc':
                        $order_sql = "ORDER BY nombre ASC";
                        break;
                    case 'nombre_desc':
                        $order_sql = "ORDER BY nombre DESC";
                        break;
                }
            }
        }

        // Traer los platos (ordenados si hay cliente, normal si no)
        $stmtOrd = $conexion->query("SELECT * FROM menu $order_sql");
        $platosOrd = $stmtOrd->fetchAll(PDO::FETCH_ASSOC);
        ?>

    <!-- Menú -->
<div id="menu" class="container my-5"> 
    <h1 class="text-center mb-5">Nuestro menú</h1>
        
        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cliente'): ?>
        <!-- Selector de ordenamiento -->
        <form method="get" class="mb-4 d-flex align-items-center">
            <label for="orden" class="text-white fw-bold me-2 mb-0">Ordenar por:</label>
            <select name="orden" id="orden" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Sin ordenar</option>
                <option value="precio_asc" <?= $ordenSeleccionado=='precio_asc'?'selected':'' ?>>Precio ↑</option>
                <option value="precio_desc" <?= $ordenSeleccionado=='precio_desc'?'selected':'' ?>>Precio ↓</option>
                <option value="nombre_asc" <?= $ordenSeleccionado=='nombre_asc'?'selected':'' ?>>Nombre A-Z</option>
                <option value="nombre_desc" <?= $ordenSeleccionado=='nombre_desc'?'selected':'' ?>>Nombre Z-A</option>
            </select>
        </form>   
        <?php endif; ?>

    <div class="row g-4">
        <?php
            //////////////$stmt = $conexion->query("SELECT * FROM menu");
            /////////////////$platos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($platosOrd as $plato) {
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
                
                // Botón de carrito solo si cliente
                if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cliente') { ?>
                    <form method="post" action="/cliente/carrito.php">
                        <input type="hidden" name="menu_id" value="<?= $plato['id'] ?>">
                        <input type="hidden" name="accion" value="agregar">
                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-dark">🛒 Agregar al carrito</button>
                        </div>
                    </form>
                    
                <?php
                    // Verificar si el plato ya está en favoritos
                    $stmtFav = $conexion->prepare("SELECT * FROM favoritos WHERE cliente_id = :cliente_id AND menu_id = :menu_id");
                    $stmtFav->execute([
                        ':cliente_id' => $_SESSION['id'],
                        ':menu_id' => $plato['id']
                    ]);
                    $esta_fav = $stmtFav->rowCount() > 0;
                    ?>
                    <form method="post" action="/agregar_favorito.php">
                        <input type="hidden" name="menu_id" value="<?= $plato['id'] ?>">
                        <input type="hidden" name="accion" value="<?= $esta_fav ? 'quitar' : 'agregar' ?>">
                        <input type="hidden" name="origen" value="index.php">
                        <div class="d-grid gap-2 mt-3">
                            <?php if ($esta_fav): ?>
                                <button type="submit" class="btn btn-outline-secondary">❌ Quitar de favoritos</button>
                            <?php else: ?>
                                <button type="submit" class="btn btn-outline-danger">❤ Agregar a favoritos</button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php
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
