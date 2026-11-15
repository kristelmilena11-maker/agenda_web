<?php
// eliminar.php - Eliminar un contacto de forma segura
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $contacto_id = $_GET['id'];

    // Eliminar el contacto únicamente si pertenece al usuario actual (Seguridad estricta)
    $sql = "DELETE FROM contactos WHERE id = :id AND id_usuario = :id_usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        'id' => $contacto_id,
        'id_usuario' => $user_id
    ]);
}

// Redirigir de vuelta al panel principal
header("Location: dashboard.php");
exit();
?>