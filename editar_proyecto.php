<?php
// iniciar sesion para mantener la sesion activa y acceder a variables de sesion
session_start();
// verificar si el usuario esta logueado, si no, redirigir al login y salir
if (!isset($_SESSION['usuario_id'])) { header("Location: login.php"); exit(); }

// crear conexion a la base de datos mysql
$conexion = new mysqli("localhost", "root", "", "crm_clientes");

// obtener el id del proyecto que viene por metodo get en la url
$id = $_GET['id'];
// buscar en la tabla proyectos el proyecto con ese id y traer sus datos como array asociativo
$proyecto = $conexion->query("SELECT * FROM proyectos WHERE id=$id")->fetch_assoc();

// si no encontro el proyecto, mostrar mensaje y detener la ejecucion
if (!$proyecto) { die("Proyecto no encontrado."); }

// si el usuario es empleado y el proyecto no le pertenece, denegar acceso
if ($_SESSION['rol'] === 'empleado' && $proyecto['empleado_id'] != $_SESSION['usuario_id']) {
    die("No tienes permisos para editar este proyecto.");
}

// si el usuario es admin, obtener la lista de clientes y empleados para el formulario
if ($_SESSION['rol'] === 'admin') {
    $clientes = $conexion->query("SELECT * FROM clientes");
    $empleados = $conexion->query("SELECT * FROM usuarios WHERE rol='empleado'");
}

// verificar si se envio el formulario con metodo post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SESSION['rol'] === 'admin') {
        // admin puede modificar todos los campos del proyecto
        $nombre = $_POST['nombre'];          // nombre nuevo enviado por formulario
        $descripcion = $_POST['descripcion']; // descripcion nueva enviada por formulario
        $estado = $_POST['estado'];           // estado nuevo enviado por formulario
        $fecha_inicio = $_POST['fecha_inicio']; // fecha inicio nueva enviada
        $fecha_fin = $_POST['fecha_fin'];     // fecha fin nueva enviada
        $cliente_id = $_POST['cliente_id'];   // cliente asignado nuevo
        $empleado_id = $_POST['empleado_id']; // empleado asignado nuevo
        // ejecutar consulta para actualizar los datos del proyecto en la bd
        $conexion->query("UPDATE proyectos SET nombre='$nombre', descripcion='$descripcion', estado='$estado', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', cliente_id='$cliente_id', empleado_id='$empleado_id' WHERE id=$id");
    } else {
        // si es empleado, solo puede cambiar el estado del proyecto
        $estado = $_POST['estado']; // nuevo estado enviado
        // actualizar solo el estado en la bd
        $conexion->query("UPDATE proyectos SET estado='$estado' WHERE id=$id");
    }
    // despues de actualizar redirigir a dashboard para evitar reenviar formulario
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Proyecto</title>
    <style>
        /* estilos basicos para la pagina y formulario */
        body { font-family: Arial; background:#121212; color:#fff; text-align:center; }
        form { max-width:400px; margin:50px auto; padding:20px; background:#1f1f1f; border-radius:8px; }
        label { display:block; text-align:left; margin-bottom:5px; }
        input, select, textarea { width:100%; padding:8px; margin-bottom:15px; border-radius:5px; border:none; }
        button { background:#6200ea; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer; }
        button:hover { background:#3700b3; }
    </style>
</head>
<body>
<h2>Editar Proyecto</h2>
<form method="POST">
    <?php if ($_SESSION['rol'] === 'admin'): ?>
        <!-- formulario para admin, que puede editar todos los campos -->
        <label>Nombre del proyecto:</label>
        <input type="text" name="nombre" value="<?= $proyecto['nombre'] ?>" required>

        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $proyecto['descripcion'] ?></textarea>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="no comenzado" <?= $proyecto['estado']=='no comenzado'?'selected':'' ?>>No comenzado</option>
            <option value="en progreso" <?= $proyecto['estado']=='en progreso'?'selected':'' ?>>En progreso</option>
            <option value="finalizado" <?= $proyecto['estado']=='finalizado'?'selected':'' ?>>Finalizado</option>
        </select>

        <label>Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" value="<?= $proyecto['fecha_inicio'] ?>" required>

        <label>Fecha de fin:</label>
        <input type="date" name="fecha_fin" value="<?= $proyecto['fecha_fin'] ?>" required>

        <label>Cliente:</label>
        <select name="cliente_id" required>
            <!-- listar todos los clientes para seleccionar -->
            <?php while ($c = $clientes->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$proyecto['cliente_id']?'selected':'' ?>><?= $c['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Asignar a empleado:</label>
        <select name="empleado_id" required>
            <!-- listar empleados para asignar -->
            <?php while ($e = $empleados->fetch_assoc()): ?>
                <option value="<?= $e['id'] ?>" <?= $e['id']==$proyecto['empleado_id']?'selected':'' ?>><?= $e['nombre'] ?></option>
            <?php endwhile; ?>
        </select>
    <?php else: ?>
        <!-- formulario para empleado, que solo puede cambiar estado -->
        <label>Estado:</label>
        <select name="estado" required>
            <option value="no comenzado" <?= $proyecto['estado']=='no comenzado'?'selected':'' ?>>No comenzado</option>
            <option value="en progreso" <?= $proyecto['estado']=='en progreso'?'selected':'' ?>>En progreso</option>
            <option value="finalizado" <?= $proyecto['estado']=='finalizado'?'selected':'' ?>>Finalizado</option>
        </select>
    <?php endif; ?>
    <button type="submit">Guardar</button>
</form>
</body>
</html>