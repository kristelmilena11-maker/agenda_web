<?php
// editar.php - Modificar un contacto existente con validación de seguridad
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$mensaje = "";
$contacto = null;

// Obtener el ID del contacto desde la URL
if (isset($_GET['id'])) {
    $contacto_id = $_GET['id'];

    // Consultar el contacto asegurando que pertenezca al usuario actual (Seguridad estricta)
    $sql = "SELECT * FROM contactos WHERE id = :id AND id_usuario = :id_usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->execute(['id' => $contacto_id, 'id_usuario' => $user_id]);
    $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$contacto) {
        header("Location: dashboard.php");
        exit();
    }
}

// Procesar la actualización cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contacto_id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $ciudad = trim($_POST['ciudad']);

    if (empty($nombre) || empty($telefono)) {
        $mensaje = "El nombre y el teléfono son obligatorios.";
    } else {
        $sql_update = "UPDATE contactos SET nombre = :nombre, telefono = :telefono, correo = :correo, ciudad = :ciudad WHERE id = :id AND id_usuario = :id_usuario";
        $stmt_update = $conexion->prepare($sql_update);
        
        if ($stmt_update->execute([
            'nombre' => $nombre,
            'telefono' => $telefono,
            'correo' => $correo,
            'ciudad' => $ciudad,
            'id' => $contacto_id,
            'id_usuario' => $user_id
        ])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $mensaje = "Ocurrió un error al actualizar el contacto.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contacto</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { display: block; width: 100%; padding: 10px; background-color: #ffc107; color: #333; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn:hover { background-color: #e0a800; }
        .mensaje { text-align: center; margin-bottom: 15px; color: #d9534f; font-size: 14px; }
        .back { text-align: center; margin-top: 15px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Editar Contacto</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <?php if ($contacto): ?>
            <form action="editar.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $contacto['id']; ?>">
                <div class="form-group">
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($contacto['nombre']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($contacto['telefono']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($contacto['correo']); ?>">
                </div>
                <div class="form-group">
                    <label>Ciudad:</label>
                    <input type="text" name="ciudad" value="<?php echo htmlspecialchars($contacto['ciudad']); ?>">
                </div>
                <button type="submit" class="btn">Actualizar Contacto</button>
            </form>
        <?php endif; ?>
        <div class="back">
            <a href="dashboard.php">Cancelar / Volver</a>
        </div>
    </div>
</body>
</html>