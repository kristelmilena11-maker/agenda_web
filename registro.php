<?php
// registro.php - Formulario y lógica de registro de usuarios
require_once 'conexion.php';

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Validación básica en servidor
    if (empty($nombre) || empty($correo) || empty($password)) {
        $mensaje = "Por favor, complete todos los campos.";
    } else {
        // Verificar si el correo ya existe en la base de datos
        $sql_check = "SELECT id FROM usuarios WHERE correo = :correo";
        $stmt = $conexion->prepare($sql_check);
        $stmt->execute(['correo' => $correo]);

        if ($stmt->rowCount() > 0) {
            $mensaje = "El correo electrónico ya está registrado.";
        } else {
            // Encriptar la contraseña de forma segura como pide el deber
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario
            $sql_insert = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
            $stmt_insert = $conexion->prepare($sql_insert);
            
            if ($stmt_insert->execute(['nombre' => $nombre, 'correo' => $correo, 'password' => $password_hash])) {
                $mensaje = "¡Registro exitoso! Ya puede <a href='login.php'>iniciar sesión</a>.";
            } else {
                $mensaje = "Ocurrió un error al registrar el usuario.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 350px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; }
        input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { display: block; width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #218838; }
        .mensaje { text-align: center; margin-bottom: 15px; color: #d9534f; font-size: 14px; }
        .back { text-align: center; margin-top: 15px; }
        .back a { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Registro</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <form action="registro.php" method="POST">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Registrarse</button>
        </form>
        <div class="back">
            <a href="index.php">Volver al inicio</a>
        </div>
    </div>
</body>
</html>