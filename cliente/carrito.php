<?php session_start();?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/header.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/navbar.php'; ?>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/bd.php'; ?>

<?php

// Verificar login
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Manejo de acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion === 'agregar' && isset($_POST['menu_id'])) {
        $menu_id = intval($_POST['menu_id']);
        $_SESSION['carrito'][$menu_id] = ($_SESSION['carrito'][$menu_id] ?? 0) + 1;

    } elseif ($accion === 'quitar' && isset($_POST['menu_id'])) {
        $menu_id = intval($_POST['menu_id']);
        if (isset($_SESSION['carrito'][$menu_id])) {
            $_SESSION['carrito'][$menu_id]--;
            if ($_SESSION['carrito'][$menu_id] <= 0) {
                unset($_SESSION['carrito'][$menu_id]);
            }
        }

    } elseif ($accion === 'eliminar' && isset($_POST['menu_id'])) {
        $menu_id = intval($_POST['menu_id']);
        unset($_SESSION['carrito'][$menu_id]);

    } elseif ($accion === 'confirmar') {
        if (!empty($_SESSION['carrito'])) {
            $cliente_id = $_SESSION['id'];
            $total = 0;

            // Calcular total
            foreach ($_SESSION['carrito'] as $menu_id => $cantidad) {
                $stmt = $conexion->prepare("SELECT precio FROM menu WHERE id = ?");
                $stmt->execute([$menu_id]);
                $precio = $stmt->fetchColumn();
                $total += $precio * $cantidad;
            }

            // Insertar orden
            $stmt = $conexion->prepare("INSERT INTO ordenes (cliente_id, total) VALUES (?, ?)");
            $stmt->execute([$cliente_id, $total]);
            $orden_id = $conexion->lastInsertId();

            // Insertar detalle
            foreach ($_SESSION['carrito'] as $menu_id => $cantidad) {
                $stmt = $conexion->prepare("SELECT precio FROM menu WHERE id = ?");
                $stmt->execute([$menu_id]);
                $precio = $stmt->fetchColumn();

                $stmtDet = $conexion->prepare("INSERT INTO orden_detalle (orden_id, menu_id, cantidad, precio_unitario) 
                                               VALUES (?, ?, ?, ?)");
                $stmtDet->execute([$orden_id, $menu_id, $cantidad, $precio]);
            }

            // Obtener email usuario
            $stmtUser = $conexion->prepare("SELECT mail FROM usuarios WHERE id = ?");
            $stmtUser->execute([$cliente_id]);
            $correoUsuario = $stmtUser->fetchColumn();

            // Vaciar carrito
            $_SESSION['carrito'] = [];

            // Mostrar alerta
            echo "<script>alert('Se envió correo de confirmación a $correoUsuario'); window.location.href='carrito.php';</script>";
            exit();
        }
    }
}

// Traer los platos que están en el carrito
$ids = array_keys($_SESSION['carrito']);
$platos = [];
if (count($ids) > 0) {
    $inQuery = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conexion->prepare("SELECT * FROM menu WHERE id IN ($inQuery)");
    $stmt->execute($ids);
    $platos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito</title>
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
    <h1 class="text-center mb-5">🛒 Mi Carrito</h1>

    <?php if (empty($_SESSION['carrito'])): ?>
        <p class="text-center">Tu carrito está vacío.</p>
    <?php else: ?>
        <table class="table table-dark table-striped text-center align-middle">
            <thead>
                <tr>
                    <th>Plato</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($platos as $plato):
                    $cantidad = $_SESSION['carrito'][$plato['id']];
                    $subtotal = $plato['precio'] * $cantidad;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($plato['nombre']) ?></td>
                    <td>$<?= number_format($plato['precio'], 2) ?></td>
                    <td><?= $cantidad ?></td>
                    <td>$<?= number_format($subtotal, 2) ?></td>
                    <td>
                        <form method="post" action="carrito.php" class="d-inline">
                            <input type="hidden" name="menu_id" value="<?= $plato['id'] ?>">
                            <input type="hidden" name="accion" value="agregar">
                            <button type="submit" class="btn btn-success btn-sm">➕</button>
                        </form>
                        <form method="post" action="carrito.php" class="d-inline">
                            <input type="hidden" name="menu_id" value="<?= $plato['id'] ?>">
                            <input type="hidden" name="accion" value="quitar">
                            <button type="submit" class="btn btn-warning btn-sm">➖</button>
                        </form>
                        <form method="post" action="carrito.php" class="d-inline">
                            <input type="hidden" name="menu_id" value="<?= $plato['id'] ?>">
                            <input type="hidden" name="accion" value="eliminar">
                            <button type="submit" class="btn btn-danger btn-sm">❌</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr class="fw-bold">
                    <td colspan="3">TOTAL</td>
                    <td colspan="2">$<?= number_format($total, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Botón Confirmar compra -->
        <form method="post" action="carrito.php" class="text-center mt-4">
            <input type="hidden" name="accion" value="confirmar">
            <button type="submit" class="btn btn-primary btn-lg">
                ✅ Confirmar compra
            </button>
        </form>
    <?php endif; ?>
</div>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/footer.php'; ?>
</body>
</html>
