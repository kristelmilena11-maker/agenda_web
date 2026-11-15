<?php
// index.php - Página principal de bienvenida
session_start();
// Si ya hay una sesión activa, lo mandamos directo al dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agenda Personal - Inicio</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { color: #333; }
        .btn { display: block; width: 100%; padding: 10px; margin: 10px 0; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; box-sizing: border-box; }
        .btn:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #28a745; }
        .btn-secondary:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Mi Agenda Personal</h2>
        <p>Bienvenido, por favor ingrese o regístrese.</p>
        <a href="login.php" class="btn">Iniciar Sesión</a>
        <a href="registro.php" class="btn btn-secondary">Registrarse</a>
    </div>
</body>
</html>