<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/bd.php';

// Solo admins
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: /index.php");
    exit;
}

// Si se envía un ID por GET, borrar ese menú
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // DEBUG: verificar que id llegue correctamente
    // var_dump($id); exit;

    // Buscar la foto del menú
    $stmt = $conexion->prepare("SELECT foto FROM menu WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($menu) {
        // Borro la foto físicamente
        $fotoRuta = $_SERVER['DOCUMENT_ROOT'] . '/' . $menu['foto'];
        if (file_exists($fotoRuta)) {
            unlink($fotoRuta);
        }

        // Borro el registro de la BD con try/catch
        try {
            $stmt = $conexion->prepare("DELETE FROM menu WHERE id = :id");
            $stmt->execute([':id' => $id]);

            if($stmt->rowCount() > 0){
                header("Location: eliminar_menu.php?msg=eliminado");
                exit;
            } else {
                echo "No se eliminó ningún registro. ID: $id";
                exit;
            }
        } catch (PDOException $e) {
            echo "Error al eliminar el registro: " . $e->getMessage();
            exit;
        }
    } else {
        echo "No se encontró el menú con ID = $id";
        exit;
    }
}

// Traer todos los menús para listarlos
$stmt = $conexion->query("SELECT * FROM menu ORDER BY id DESC");
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Eliminar Menú - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background-color: #343a40; color: white; }
</style>
</head>
<body>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>

<div class="container py-5">
    <h1 class="text-center mb-4">Eliminar Menú</h1>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'eliminado'): ?>
        <div class="alert alert-success">Menú eliminado correctamente.</div>
    <?php endif; ?>

    <div class="row">
        <?php if(count($menus) === 0): ?>
            <p class="text-center">No hay menús disponibles.</p>
        <?php endif; ?>

        <?php foreach($menus as $menu): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-lg border-0">
                    <img src="/<?= htmlspecialchars($menu['foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['nombre']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($menu['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($menu['descripcion']) ?></p>
                        <p class="card-text mt-auto"><strong>Precio: $<?= number_format($menu['precio'], 2) ?></strong></p>

                        <a href="eliminar_menu.php?id=<?= $menu['id'] ?>"
                           class="btn btn-danger mt-2"
                           onclick="return confirm('¿Seguro que quieres eliminar este menú?');">
                           Borrar
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