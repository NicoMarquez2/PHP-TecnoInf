<?php
$uri = "mysql://avnadmin:AVNS_LzQfO5qeO4ng60vElzt@taller-php-paplaboratorio1.g.aivencloud.com:10513/defaultdb?ssl-mode=REQUIRED";

// Parsear la URI
$fields = parse_url($uri);

// Construir el DSN incluyendo SSL
$dsn = "mysql:host={$fields['host']};port={$fields['port']};dbname=defaultdb;charset=utf8mb4;sslmode=verify-ca;sslrootcert=ca.pem";

try {
    $conexion = new PDO($dsn, $fields['user'], $fields['pass']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión remota segura establecida";

    // Ejemplo: verificar versión de MySQL
    $stmt = $conexion->query("SELECT VERSION()");
    echo "\nMySQL version: " . $stmt->fetch()[0];

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

