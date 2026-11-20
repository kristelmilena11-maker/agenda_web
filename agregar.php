<?php
// agregar.php - Formulario y lógica para registrar un nuevo contacto
require_once 'conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_SESSION['user_id'];
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $ciudad = trim($_POST['ciudad']);
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    // Validación básica en servidor
    if (empty($nombre) || empty($telefono)) {
        $mensaje = "El nombre y el teléfono son obligatorios.";
    } else {
        // Insertar el contacto vinculado al usuario actual incluyendo favorito
        $sql = "INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad, favorito) VALUES (:id_usuario, :nombre, :telefono, :correo, :ciudad, :favorito)";
        $stmt = $conexion->prepare($sql);
        
        if ($stmt->execute([
            'id_usuario' => $id_usuario,
            'nombre' => $nombre,
            'telefono' => $telefono,
            'correo' => $correo,
            'ciudad' => $ciudad,
            'favorito' => $favorito
        ])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $mensaje = "Ocurrió un error al guardar el contacto.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Contacto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input[type="text"], input[type="email"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .checkbox-group { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
        .checkbox-group input[type="checkbox"] { width: auto; margin: 0; cursor: pointer; }
        .checkbox-group label { margin: 0; color: #333; cursor: pointer; }
        .btn { display: block; width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #218838; }
        .mensaje { text-align: center; margin-bottom: 15px; color: #d9534f; font-size: 14px; }
        .back { text-align: center; margin-top: 15px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Nuevo Contacto</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <form action="agregar.php" method="POST">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo">
            </div>
            <div class="form-group">
                <label>Ciudad:</label>
                <input type="text" name="ciudad">
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="favorito" id="favorito" value="1">
                <label for="favorito">Marcar como contacto favorito</label>
            </div>
            <button type="submit" class="btn">Guardar Contacto</button>
        </form>
        <div class="back">
            <a href="dashboard.php">Cancelar / Volver</a>
        </div>
    </div>
</body>
</html>