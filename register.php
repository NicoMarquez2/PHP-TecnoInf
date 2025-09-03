<?php
require 'bd.php';
session_start();

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Verificar si el mail ya existe
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE mail = :mail LIMIT 1");
        $stmt->execute([':mail' => $email]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            $mensaje = "❌ Este correo ya está registrado";
        } else {
            // Insertar usuario nuevo
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, mail, contrasena, tipo) VALUES (:nombre, :mail, :password, 'cliente')");
            $stmt->execute([
                ':nombre' => $nombre,
                ':mail' => $email,
                ':password' => $password
            ]);

            $mensaje = "✅ Registro completado. Ahora podés iniciar sesión.";
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
<title>Registrarse</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

<div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h2 class="text-center mb-4">Registrarse</h2>

    <?php if($mensaje): ?>
        <div class="alert <?= strpos($mensaje, '✅') !== false ? 'alert-success' : 'alert-danger' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Completar registro</button>
            <a href="login.php" class="btn btn-secondary">Atrás</a>
            <a href="index.php" class="btn btn-outline-dark">Volver al inicio</a>
        </div>
    </form>
</div>

</body>
</html>


