<?php session_start();?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/bd.php'; ?>
<link rel="stylesheet" href="../estilo.css">

<?php
// Verificar login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

$cliente_id = $_SESSION['id'];

// Traer órdenes del usuario
$stmt = $conexion->prepare("
    SELECT o.id, o.fecha, o.total
    FROM ordenes o
    WHERE o.cliente_id = ?
    ORDER BY o.fecha DESC
");
$stmt->execute([$cliente_id]);
$ordenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
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
    <h1 class="text-center mb-5">📜 Historial de Compras</h1>

    <?php if (empty($ordenes)): ?>
        <p class="text-center">Todavía no realizaste ninguna compra.</p>
    <?php else: ?>
        <?php foreach ($ordenes as $orden): ?>
            <div class="card bg-dark text-white mb-4 shadow">
                <div class="card-header">
                    <strong>Orden #<?= $orden['id'] ?></strong>  
                    <span class="float-end"><?= $orden['fecha'] ?></span>
                </div>
                <div class="card-body">
                    <table class="table table-dark table-striped text-center align-middle mb-0 table-fixed">
                        <thead>
                            <tr>
                                <th>Plato</th>
                                <th>Precio Unitario</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmtDet = $conexion->prepare("
                                SELECT d.cantidad, d.precio_unitario, m.nombre
                                FROM orden_detalle d
                                JOIN menu m ON d.menu_id = m.id
                                WHERE d.orden_id = ?
                            ");
                            $stmtDet->execute([$orden['id']]);
                            $detalles = $stmtDet->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($detalles as $detalle):
                                $subtotal = $detalle['precio_unitario'] * $detalle['cantidad'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($detalle['nombre']) ?></td>
                                <td>$<?= number_format($detalle['precio_unitario'], 2) ?></td>
                                <td><?= $detalle['cantidad'] ?></td>
                                <td>$<?= number_format($subtotal, 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr class="fw-bold">
                                <td colspan="3">TOTAL</td>
                                <td>$<?= number_format($orden['total'], 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>


<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
