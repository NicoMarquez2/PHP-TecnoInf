<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'] . '/bd.php'; 

// Solo admins
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];

    // Manejo de la subida de la imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileSize = $_FILES['foto']['size'];
        $fileType = $_FILES['foto']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Directorio donde se guardarán las imágenes subidas
        $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/images/';
        $dest_path = $uploadFileDir . $fileName;

        // Mover el archivo subido al directorio deseado
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            // Insertar el nuevo menú en la base de datos
            $stmt = $conexion->prepare("INSERT INTO menu (nombre, descripcion, precio, foto) VALUES (:nombre, :descripcion, :precio, :foto)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':foto' => 'images/' . $fileName // Guardamos la ruta relativa
            ]);

            // Redirigir a la página del menú después de agregar
            header("Location: /menu.php");
            exit;
        } else {
            echo "Error al mover el archivo subido.";
        }
    } else {
        echo "Error en la subida del archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar menú</title>
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
                        <h1 class="text-center mb-4">Agregar Menú</h1>
                        
                        <form action="agregar_menu.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre del menú</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="precio" class="form-label">Precio ($)</label>
                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto del menú</label>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/*" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark btn-lg">
                                    <i class="bi bi-save"></i> Guardar menú
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
