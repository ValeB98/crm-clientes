<?php
// iniciar sesion para acceder a variables de sesion
session_start();

// si no hay usuario logueado, redirigir a login y salir
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }

// crear conexion a la base de datos
$conexion = new mysqli("localhost", "root", "", "crm_clientes");

// si hubo error en la conexion, mostrar mensaje y detener ejecucion
if ($conexion->connect_error) { die("error de conexion: " . $conexion->connect_error); }

// obtener id y rol del usuario logueado desde la sesion
$usuario_id = $_SESSION['usuario_id'];
$rol = $_SESSION['rol'];

// si se recibio un formulario post para actualizar estado del proyecto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proyecto_id']) && isset($_POST['estado'])) {
    $proyecto_id = $_POST['proyecto_id'];
    $estado = $_POST['estado'];

    // si es empleado, solo puede actualizar el estado de sus proyectos
    if ($rol === 'empleado') {
        $conexion->query("UPDATE proyectos SET estado='$estado' WHERE id=$proyecto_id AND empleado_id=$usuario_id");
    }
    // si es admin, puede actualizar estado, empleado asignado y prioridad
    if ($rol === 'admin' && isset($_POST['empleado_id']) && isset($_POST['prioridad'])) {
        $empleado_id = $_POST['empleado_id'];
        $prioridad = $_POST['prioridad'];
        $conexion->query("UPDATE proyectos SET estado='$estado', empleado_id=$empleado_id, prioridad='$prioridad' WHERE id=$proyecto_id");
    }
}

// si el admin quiere eliminar un proyecto (por parametro get)
if ($rol === 'admin' && isset($_GET['eliminar'])) {
    $id_eliminar = $_GET['eliminar'];
    $conexion->query("DELETE FROM proyectos WHERE id=$id_eliminar");
}

// si el admin quiere eliminar un cliente (por parametro get)
if ($rol === 'admin' && isset($_GET['eliminar_cliente'])) {
    $id_cliente = $_GET['eliminar_cliente'];
    $conexion->query("DELETE FROM clientes WHERE id=$id_cliente");
}

// consultar todos los clientes para mostrar
$clientes = $conexion->query("SELECT * FROM clientes");

// consultar proyectos, si es admin muestra todos, si es empleado solo los asignados
if ($rol === 'admin') {
    $proyectos = $conexion->query("SELECT proyectos.*, clientes.nombre AS cliente_nombre FROM proyectos JOIN clientes ON proyectos.cliente_id = clientes.id");
} else {
    $proyectos = $conexion->query("SELECT proyectos.*, clientes.nombre AS cliente_nombre FROM proyectos JOIN clientes ON proyectos.cliente_id = clientes.id WHERE empleado_id=$usuario_id");
}

// obtener lista de empleados para asignar proyectos (solo admin)
$empleados = [];
if ($rol === 'admin') {
    $empleados = $conexion->query("SELECT id, nombre FROM usuarios WHERE rol='empleado'");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        /* estilos basicos para la pagina */
        body { font-family: Arial, sans-serif; background: #1e1e1e; color: #f0f0f0; margin: 0; padding: 20px; }
        h2 { text-align: center; }
        a { color: #4cafef; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { padding: 6px 12px; background: #4cafef; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #357ac8; }
        .container { max-width: 900px; margin: auto; }
        .card { background: #2c2c2c; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        label { display: block; margin-top: 5px; }
        select, input[type="text"], input[type="email"], input[type="tel"] { width: 100%; padding: 5px; margin-top: 3px; border: none; border-radius: 5px; background: #3c3c3c; color: white; }
        .actions { display: flex; gap: 10px; margin-top: 10px; }
        .logout { float: right; }
    </style>
</head>
<body>
<div class="container">
    <!-- mostrar nombre y rol del usuario logueado -->
    <h2>bienvenido <?= $_SESSION['nombre'] ?> (<?= $_SESSION['rol'] ?>)</h2>
    <!-- boton para cerrar sesion -->
    <a class="logout" href="logout.php">cerrar sesion</a>
    
    <!-- si es admin mostrar botones para agregar cliente y proyecto -->
    <?php if ($rol === 'admin'): ?>
        <a class="btn" href="agregar_cliente.php">agregar cliente</a>
        <a class="btn" href="agregar_proyecto.php">agregar proyecto</a>
    <?php endif; ?>

    <h3>lista de proyectos</h3>
    <!-- recorrer lista de proyectos para mostrarlos -->
    <?php while ($p = $proyectos->fetch_assoc()): ?>
        <div class="card">
            <h4><?= $p['nombre'] ?> (cliente: <?= $p['cliente_nombre'] ?>)</h4>
            <p><?= $p['descripcion'] ?></p>
            <form method="POST">
                <input type="hidden" name="proyecto_id" value="<?= $p['id'] ?>">

                <label>estado:</label>
                <select name="estado">
                    <option <?= $p['estado'] == 'No iniciado' ? 'selected' : '' ?>>no iniciado</option>
                    <option <?= $p['estado'] == 'En progreso' ? 'selected' : '' ?>>en progreso</option>
                    <option <?= $p['estado'] == 'Finalizado' ? 'selected' : '' ?>>finalizado</option>
                </select>

                <!-- si es admin puede asignar empleado y prioridad -->
                <?php if ($rol === 'admin'): ?>
                    <label>empleado asignado:</label>
                    <select name="empleado_id">
                        <option value="">sin asignar</option>
                        <!-- listar empleados para seleccionar -->
                        <?php while ($e = $empleados->fetch_assoc()): ?>
                            <option value="<?= $e['id'] ?>" <?= $p['empleado_id'] == $e['id'] ? 'selected' : '' ?>><?= $e['nombre'] ?></option>
                        <?php endwhile; $empleados->data_seek(0); ?>
                    </select>

                    <label>prioridad:</label>
                    <select name="prioridad">
                        <option <?= $p['prioridad'] == 'Alta' ? 'selected' : '' ?>>alta</option>
                        <option <?= $p['prioridad'] == 'Media' ? 'selected' : '' ?>>media</option>
                        <option <?= $p['prioridad'] == 'Baja' ? 'selected' : '' ?>>baja</option>
                    </select>
                <?php endif; ?>

                <div class="actions">
                    <!-- boton para actualizar proyecto -->
                    <button class="btn" type="submit">actualizar</button>
                    <!-- si es admin mostrar boton para eliminar proyecto -->
                    <?php if ($rol === 'admin'): ?>
                        <a class="btn" href="?eliminar=<?= $p['id'] ?>" onclick="return confirm('¿eliminar este proyecto?')">eliminar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    <?php endwhile; ?>

    <!-- si es admin mostrar lista de clientes -->
    <?php if ($rol === 'admin'): ?>
        <h3>lista de clientes</h3>
        <?php while ($c = $clientes->fetch_assoc()): ?>
            <div class="card">
                <h4><?= $c['nombre'] ?> (<?= $c['empresa'] ?>)</h4>
                <p>correo: <?= $c['correo'] ?> | tel: <?= $c['telefono'] ?> | ciudad: <?= $c['ciudad'] ?></p>
                <div class="actions">
                    <!-- botones para editar o eliminar cliente -->
                    <a class="btn" href="editar_cliente.php?id=<?= $c['id'] ?>">editar</a>
                    <a class="btn" href="?eliminar_cliente=<?= $c['id'] ?>" onclick="return confirm('¿eliminar este cliente?')">eliminar</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
</body>
</html>