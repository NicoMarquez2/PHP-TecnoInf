<?php session_start();?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/bd.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis favoritos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40 !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="container my-5"> 
        <h1 class="text-center mb-5">❤️ Mis favoritos</h1>

        <div class="row g-4">
            <?php
            if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'cliente') {
                echo '<p class="text-center">Por favor, inicia sesión como cliente para ver tus favoritos.</p>';
            } else {
                $cliente_id = $_SESSION['id'];
                $stmt = $conexion->prepare("
                    SELECT m.* 
                    FROM menu m 
                    JOIN favoritos f ON m.id = f.menu_id 
                    WHERE f.cliente_id = :cliente_id
                ");
                $stmt->execute([':cliente_id' => $cliente_id]);
                $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($favoritos) === 0) {
                    echo '<p class="text-center">No tienes platos favoritos aún.</p>';
                } else {
                    foreach ($favoritos as $plato) {
                        echo '<div class="col-md-4">';
                        echo '  <div class="card h-100 shadow border-0 rounded-3">';

                        echo '      <img src="/' . htmlspecialchars($plato['foto']) . '" 
                                          class="card-img-top" 
                                          alt="' . htmlspecialchars($plato['nombre']) . '" 
                                          style="height: 220px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">';

                        echo '      <div class="card-body d-flex flex-column">';
                        echo '          <h5 class="card-title fw-bold">' . htmlspecialchars($plato['nombre']) . '</h5>';
                        echo '          <p class="card-text">' . htmlspecialchars($plato['descripcion']) . '</p>';
                        echo '          <p class="card-text mt-auto"><strong>Precio: $' . number_format($plato['precio'], 2) . '</strong></p>';
                        
                        // Botón Quitar de favoritos
                        echo '<form method="POST" action="/agregar_favorito.php">';
                        echo '    <input type="hidden" name="menu_id" value="' . intval($plato['id']) . '">';
                        echo '    <input type="hidden" name="accion" value="quitar">';
                        echo '    <input type="hidden" name="origen" value="cliente/favoritos.php">';
                        echo '    <div class="d-grid gap-2 mt-3">';
                        echo '        <button type="submit" class="btn btn-outline-secondary">❌ Quitar de favoritos</button>';
                        echo '    </div>';
                        echo '</form>';

                        // Botón Agregar al carrito (solo cliente logueado)
                        if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'cliente') {
                            echo '<form method="post" action="/cliente/carrito.php">';
                            echo '    <input type="hidden" name="menu_id" value="' . intval($plato['id']) . '">';
                            echo '    <input type="hidden" name="accion" value="agregar">';
                            echo '    <div class="d-grid gap-2 mt-3">';
                            echo '        <button type="submit" class="btn btn-dark">🛒 Agregar al carrito</button>';
                            echo '    </div>';
                            echo '</form>';
                        }

                        echo '      </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
