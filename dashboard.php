<?php
// dashboard.php - Panel principal de la agenda (restringido a usuarios autenticados)
require_once 'conexion.php';
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_nombre = $_SESSION['user_nombre'];
$user_correo = $_SESSION['user_correo'];

// Obtener únicamente los contactos del usuario autenticado
$sql = "SELECT * FROM contactos WHERE id_usuario = :id_usuario ORDER BY nombre ASC";
$stmt = $conexion->prepare($sql);
$stmt->execute(['id_usuario' => $user_id]);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Agenda - Panel Principal</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; }
        .user-info h3 { margin: 0; color: #333; }
        .user-info p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .btn { padding: 8px 15px; border-radius: 4px; text-decoration: none; color: white; font-size: 14px; }
        .btn-primary { background-color: #28a745; }
        .btn-primary:hover { background-color: #218838; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-warning { background-color: #ffc107; color: #333; }
        .btn-warning:hover { background-color: #e0a800; }
        .btn-logout { background-color: #6c757d; }
        .btn-logout:hover { background-color: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions { display: flex; gap: 5px; }
        .empty { text-align: center; color: #666; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="user-info">
                <h3>Bienvenido, <?php echo htmlspecialchars($user_nombre); ?></h3>
                <p>Correo: <?php echo htmlspecialchars($user_correo); ?></p>
            </div>
            <div>
                <a href="agregar.php" class="btn btn-primary">+ Nuevo Contacto</a>
                <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
            </div>
        </div>

        <h2>Mis Contactos</h2>
        
        <?php if (count($contactos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Ciudad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $contacto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['correo']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['ciudad']); ?></td>
                            <td class="actions">
                                <a href="editar.php?id=<?php echo $contacto['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar.php?id=<?php echo $contacto['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este contacto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">No tienes contactos registrados todavía. ¡Agrega el primero!</p>
        <?php endif; ?>
    </div>
</body>
</html>