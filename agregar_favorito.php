<?php
session_start();
require 'bd.php';

// Verificar login y tipo
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'cliente') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'], $_POST['accion'])) {
    $cliente_id = $_SESSION['id'];
    $menu_id = intval($_POST['menu_id']);
    $accion = $_POST['accion'];
    $origen = isset($_POST['origen']) ? $_POST['origen'] : 'index.php';

    if ($accion === 'agregar') {
        // Agregar a favoritos si no existe
        $stmt = $conexion->prepare("SELECT * FROM favoritos WHERE cliente_id = :cliente_id AND menu_id = :menu_id");
        $stmt->execute([':cliente_id' => $cliente_id, ':menu_id' => $menu_id]);
        if ($stmt->rowCount() === 0) {
            $stmt = $conexion->prepare("INSERT INTO favoritos (cliente_id, menu_id, fecha_agregado) VALUES (:cliente_id, :menu_id, NOW())");
            $stmt->execute([':cliente_id' => $cliente_id, ':menu_id' => $menu_id]);
        }
    } elseif ($accion === 'quitar') {
        // Quitar de favoritos
        $stmt = $conexion->prepare("DELETE FROM favoritos WHERE cliente_id = :cliente_id AND menu_id = :menu_id");
        $stmt->execute([':cliente_id' => $cliente_id, ':menu_id' => $menu_id]);
    }

    // Redirigir de vuelta a la página de origen
    header("Location: /" . $origen);
    exit();
}
