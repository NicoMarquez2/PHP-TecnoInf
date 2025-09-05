<?php session_start();?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/bd.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

    <div class="container mt-5"> 
        <h1 class="text-center mb-5">Nuestro menú</h1>

        <div class="row">
            <?php
                $stmt = $conexion->query("SELECT * FROM menu");
                $platos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($platos as $plato) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '  <div class="card h-100 shadow-lg border-0">';
                    echo '      <img src="' . htmlspecialchars($plato['foto']) . '" class="card-img-top" alt="' . htmlspecialchars($plato['nombre']) . '">';
                    echo '      <div class="card-body d-flex flex-column">';
                    echo '          <h5 class="card-title">' . htmlspecialchars($plato['nombre']) . '</h5>';
                    echo '          <p class="card-text">' . htmlspecialchars($plato['descripcion']) . '</p>';
                    echo '          <p class="card-text mt-auto"><strong>Precio: $' . number_format($plato['precio'], 2) . '</strong></p>';
                    echo '          <a href="#" class="btn btn-dark mt-2">Agregar al carrito</a>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>
</body>
</html>
