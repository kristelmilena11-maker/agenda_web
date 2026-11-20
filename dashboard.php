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

// Alternar estado de favorito si se envía por GET
if (isset($_GET['toggle_fav'])) {
    $contacto_id = (int) $_GET['toggle_fav'];
    $sqlToggle = "UPDATE contactos
                  SET favorito = IF(favorito = 1, 0, 1)
                  WHERE id = :id AND id_usuario = :id_usuario";
    $stmtToggle = $conexion->prepare($sqlToggle);
    $stmtToggle->execute([
        'id' => $contacto_id,
        'id_usuario' => $user_id
    ]);

    // Mantener filtros actuales al recargar
    $params = [];
    if (!empty($_GET['buscar'])) {
        $params['buscar'] = $_GET['buscar'];
    }
    if (isset($_GET['solo_fav']) && $_GET['solo_fav'] === '1') {
        $params['solo_fav'] = '1';
    }
    $queryString = !empty($params) ? '?' . http_build_query($params) : '';
    header("Location: dashboard.php" . $queryString);
    exit();
}

// Capturar filtros de búsqueda y favoritos
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$solo_fav = (isset($_GET['solo_fav']) && $_GET['solo_fav'] === '1') ? true : false;

// Construir consulta segura con PDO manteniendo el aislamiento por id_usuario
$sql = "SELECT * FROM contactos WHERE id_usuario = :id_usuario";
$parametros = ['id_usuario' => $user_id];

if ($buscar !== '') {
    $sql .= " AND (nombre LIKE :buscar1 OR correo LIKE :buscar2 OR ciudad LIKE :buscar3)";
    $termino = '%' . $buscar . '%';
    $parametros['buscar1'] = $termino;
    $parametros['buscar2'] = $termino;
    $parametros['buscar3'] = $termino;
}

if ($solo_fav) {
    $sql .= " AND favorito = 1";
}

$sql .= " ORDER BY nombre ASC";

$stmt = $conexion->prepare($sql);
$stmt->execute($parametros);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Agenda - Panel Principal</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 20px; }
        .container { max-width: 950px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #eee; padding-bottom: 15px; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .user-info h3 { margin: 0; color: #333; }
        .user-info p { margin: 5px 0 0; color: #666; font-size: 14px; }
        .btn { padding: 8px 14px; border-radius: 4px; text-decoration: none; color: white; font-size: 14px; border: none; cursor: pointer; display: inline-block; }
        .btn-primary { background-color: #28a745; }
        .btn-primary:hover { background-color: #218838; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-warning { background-color: #ffc107; color: #333; }
        .btn-warning:hover { background-color: #e0a800; }
        .btn-logout { background-color: #6c757d; }
        .btn-logout:hover { background-color: #5a6268; }
        .btn-info { background-color: #17a2b8; }
        .btn-info:hover { background-color: #138496; }
        .btn-fav-active { background-color: #f39c12; color: white; }
        .btn-fav-inactive { background-color: #e9ecef; color: #495057; }
        .filters { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 15px; background: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #e9ecef; }
        .filters input[type="text"] { flex: 1; min-width: 220px; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; font-size: 14px; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions { display: flex; gap: 5px; flex-wrap: wrap; }
        .empty { text-align: center; color: #666; padding: 20px; }
        .badge-fav { color: #e67e22; font-weight: bold; }
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

        <form method="GET" action="dashboard.php" class="filters">
            <input
                type="text"
                name="buscar"
                placeholder="Buscar por nombre, correo o ciudad..."
                value="<?php echo htmlspecialchars($buscar); ?>"
            >
            <?php if ($solo_fav): ?>
                <input type="hidden" name="solo_fav" value="1">
            <?php endif; ?>
            <button type="submit" class="btn btn-info">Buscar</button>

            <?php if ($solo_fav): ?>
                <a href="dashboard.php<?php echo $buscar !== '' ? '?buscar=' . urlencode($buscar) : ''; ?>" class="btn btn-fav-active">
                    ★ Mostrando Favoritos (Ver todos)
                </a>
            <?php else: ?>
                <a href="dashboard.php?solo_fav=1<?php echo $buscar !== '' ? '&buscar=' . urlencode($buscar) : ''; ?>" class="btn btn-fav-inactive">
                    ☆ Solo Favoritos
                </a>
            <?php endif; ?>

            <?php if ($buscar !== '' || $solo_fav): ?>
                <a href="dashboard.php" class="btn btn-logout">Limpiar</a>
            <?php endif; ?>
        </form>
        
        <?php if (count($contactos) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Fav</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Ciudad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactos as $contacto): ?>
                        <?php $es_fav = (int)($contacto['favorito'] ?? 0) === 1; ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php if ($es_fav): ?>
                                    <span class="badge-fav" title="Favorito">★</span>
                                <?php else: ?>
                                    <span style="color: #aaa;" title="No favorito">☆</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($contacto['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['correo']); ?></td>
                            <td><?php echo htmlspecialchars($contacto['ciudad']); ?></td>
                            <td class="actions">
                                <?php
                                $toggleParams = ['toggle_fav' => $contacto['id']];
                                if ($buscar !== '') {
                                    $toggleParams['buscar'] = $buscar;
                                }
                                if ($solo_fav) {
                                    $toggleParams['solo_fav'] = '1';
                                }
                                $toggleUrl = 'dashboard.php?' . http_build_query($toggleParams);
                                ?>
                                <a href="<?php echo htmlspecialchars($toggleUrl); ?>" class="btn <?php echo $es_fav ? 'btn-fav-active' : 'btn-fav-inactive'; ?>">
                                    <?php echo $es_fav ? 'Quitar Fav' : 'Favorito'; ?>
                                </a>
                                <a href="editar.php?id=<?php echo $contacto['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar.php?id=<?php echo $contacto['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Está seguro de que desea eliminar este contacto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty">
                <?php if ($buscar !== '' || $solo_fav): ?>
                    No se encontraron contactos que coincidan con tu filtro.
                <?php else: ?>
                    No tienes contactos registrados todavía. ¡Agrega el primero!
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>