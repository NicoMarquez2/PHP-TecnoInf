<?php
session_start();

// Limpiamos todas las variables de sesión
$_SESSION = [];

// Destruimos la sesión
session_destroy();

// Redirigimos al index
header("Location: index.php");
exit();
?>