<?php
$host = 'localhost';
$dbname = 'agenda_db'; // Asegúrate de que se llame así o cámbialo por el nombre que le pusiste en phpMyAdmin
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Configurar el modo de error de PDO para excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e.getMessage());
}
?>