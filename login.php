<?php
require 'bd.php';
session_start();

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    try {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE mail = :mail AND contrasena = :password LIMIT 1");
        $stmt->execute([':mail' => $mail, ':password' => $password]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuario'] = $usuario['nombre'];
            $_SESSION['tipo'] = $usuario['tipo'];

            if ($usuario['tipo'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: cliente.php");
            }
            exit;
        } else {
            $mensaje = "❌ Usuario o contraseña incorrectos";
        }

    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h2 class="text-center mb-4">Iniciar sesión</h2>

    <?php if($mensaje): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="mail" class="form-label">Mail</label>
            <input type="email" class="form-control" id="mail" name="mail" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="register.php" class="btn btn-secondary">Registrarse</a>
        </div>
    </form>
</div>

</body>
</html>
