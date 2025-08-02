<?php
// iniciar sesion o continuar sesion
session_start();

// verificar si no existe usuario_id en sesion o si el rol no es admin
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    // si no cumple, redirigir a login y terminar ejecucion
    header("Location: login.php");
    exit();
}

// crear conexion a base de datos (host, usuario, contraseña, bd)
$conexion = new mysqli("localhost", "root", "", "crm_clientes");

// consultar lista de usuarios con rol empleado para mostrar en el select
$empleados = $conexion->query("SELECT id, nombre FROM usuarios WHERE rol='empleado'");

// si el formulario fue enviado con metodo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // guardar los datos enviados del formulario en variables
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];
    $cliente_id = $_POST['cliente_id'];
    $empleado_id = $_POST['empleado_id'];
    $prioridad = $_POST['prioridad'];

    // insertar nuevo proyecto en la tabla proyectos con los datos recibidos
    $conexion->query("INSERT INTO proyectos (nombre, descripcion, estado, cliente_id, empleado_id, prioridad) 
                      VALUES ('$nombre', '$descripcion', '$estado', $cliente_id, $empleado_id, '$prioridad')");
    // redirigir al dashboard despues de guardar
    header("Location: dashboard.php");
    exit();
}

// consultar todos los clientes para mostrar en el select
$clientes = $conexion->query("SELECT * FROM clientes");
?>

<!DOCTYPE html>
<html>
<head>
    <!-- definir codificacion de caracteres -->
    <meta charset="UTF-8">
    <title>Agregar Proyecto</title>
    <style>
        /* estilos para el cuerpo: fondo oscuro, texto blanco, tipografia y centrado vertical y horizontal */
        body { background:#121212; color:#fff; font-family:Arial; display:flex; justify-content:center; align-items:center; height:100vh; }
        /* estilos para el formulario: fondo gris oscuro, padding, bordes redondeados y ancho fijo */
        form { background:#1e1e1e; padding:20px; border-radius:10px; width:400px; }
        /* estilos para inputs, textarea y selects: ancho completo, padding, margen abajo, sin borde, bordes redondeados, fondo gris y texto blanco */
        input, textarea, select { width:100%; padding:10px; margin-bottom:10px; border:none; border-radius:5px; background:#2a2a2a; color:#fff; }
        /* estilos para boton: padding, fondo verde, sin borde, texto blanco, ancho completo y bordes redondeados */
        button { padding:10px; background:#4CAF50; border:none; color:#fff; width:100%; border-radius:5px; }
        /* cambio de color del boton al pasar el cursor */
        button:hover { background:#45a049; }
        /* estilos para las etiquetas label */
        label { display:block; margin-bottom:5px; font-weight:bold; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Agregar Proyecto</h2>

        <!-- etiqueta y campo texto para nombre del proyecto -->
        <label for="nombre">Nombre del proyecto</label>
        <input type="text" name="nombre" id="nombre" required>

        <!-- etiqueta y area de texto para descripcion del proyecto -->
        <label for="descripcion">Descripcion</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        <!-- etiqueta y select para estado del proyecto -->
        <label for="estado">Estado</label>
        <select name="estado" id="estado">
            <option value="Sin iniciar">Sin iniciar</option>
            <option value="En progreso">En progreso</option>
            <option value="Finalizado">Finalizado</option>
        </select>

        <!-- etiqueta y select para prioridad -->
        <label for="prioridad">Prioridad</label>
        <select name="prioridad" id="prioridad">
            <option value="Alta">Alta</option>
            <option value="Media" selected>Media</option>
            <option value="Baja">Baja</option>
        </select>

        <!-- etiqueta y select para elegir cliente -->
        <label for="cliente_id">Cliente</label>
        <select name="cliente_id" id="cliente_id" required>
            <?php while($c = $clientes->fetch_assoc()): ?>
                <!-- mostrar opciones con id y nombre del cliente -->
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <!-- etiqueta y select para elegir empleado asignado -->
        <label for="empleado_id">Empleado asignado</label>
        <select name="empleado_id" id="empleado_id" required>
            <?php while($e = $empleados->fetch_assoc()): ?>
                <!-- mostrar opciones con id y nombre del empleado -->
                <option value="<?= $e['id'] ?>"><?= $e['nombre'] ?></option>
            <?php endwhile; ?>
        </select>

        <!-- boton para enviar formulario -->
        <button type="submit">Guardar</button>
    </form>
</body>
</html>