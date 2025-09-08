<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/bd.php';

// Si no hay id, mostrar lista de menús para elegir
if (!isset($_GET['id'])) {
    $stmt = $conexion->query("SELECT * FROM menu ORDER BY id DESC");
    $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar Menú - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #343a40; color: white; }
        </style>
    </head>
    <body>
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

        <div class="container py-5">
            <h1 class="text-center mb-4">Modificar Menú</h1>
            <div class="row">
                <?php if(count($menus) === 0): ?>
                    <p class="text-center">No hay menús disponibles.</p>
                <?php endif; ?>

                <?php foreach($menus as $menu): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-lg border-0">
                            <img src="/<?= htmlspecialchars(ltrim($menu['foto'], '/')) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['nombre']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($menu['nombre']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($menu['descripcion']) ?></p>
                                <p class="card-text mt-auto"><strong>Precio: $<?= number_format($menu['precio'], 2) ?></strong></p>

                                <a href="modificar_menu.php?id=<?= $menu['id'] ?>"
                                   class="btn btn-primary mt-2">
                                   Editar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

$id = $_GET['id'];

// Obtener datos actuales del menú
$stmt = $conexion->prepare("SELECT * FROM menu WHERE id = :id");
$stmt->execute([':id' => $id]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    echo "Menú no encontrado.";
    exit;
}

// Procesar el formulario de edición
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $foto = $menu['foto']; // Valor por defecto

    // Manejo de la subida de la imagen (opcional)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $dest_path = $uploadFileDir . $fileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $foto = 'images/' . $fileName;
        } else {
            echo "Error al mover el archivo subido.";
        }
    }

    // Actualizar el menú en la base de datos
    $stmt = $conexion->prepare("UPDATE menu SET nombre = :nombre, descripcion = :descripcion, precio = :precio, foto = :foto WHERE id = :id");
    $stmt->execute([
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => $precio,
        ':foto' => $foto,
        ':id' => $id
    ]);

    header("Location: /menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar menú</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40 !important;
        }
    </style>
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-4">
                        <h1 class="text-center mb-4">Modificar Menú</h1>
                        
                        <form action="modificar_menu.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del menú</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($menu['nombre']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?php echo htmlspecialchars($menu['descripcion']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio ($)</label>
                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" value="<?php echo htmlspecialchars($menu['precio']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto actual</label><br>
                                <img src="/<?php echo $menu['foto']; ?>" alt="Foto actual" style="max-width:150px;"><br>
                                <label for="foto" class="form-label mt-2">Cambiar foto (opcional)</label>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/*">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="bi bi-save"></i> Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
</body>
</html>