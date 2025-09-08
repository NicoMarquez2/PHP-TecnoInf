<?php 
session_start();
//Me fijo si el usuario está logueado y es admin
if(!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 'admin'){
    header("Location: /index.php");
    exit;   
}

include $_SERVER['DOCUMENT_ROOT'] . '/bd.php';

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
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, mail, contrasena, tipo) VALUES (:nombre, :mail, :password, 'admin')");
            $stmt->execute([
                ':nombre' => $nombre,
                ':mail' => $email,
                ':password' => $password
            ]);

            $mensaje = "✅ Registro del usuario " . $nombre . " completado.";
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
    <title>Registrar admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40 !important;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="max-width: 400px; width: 100%;">
    <h2 class="text-center mb-4">Registrar nuevo usuario administrador</h2>
  
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
            <button type="submit" class="btn btn-dark">Completar registro</button>
            <a href="/index.php" class="btn btn-outline-dark">Volver al inicio</a>
        </div>
    </form>
  </div>
</body>
</html>