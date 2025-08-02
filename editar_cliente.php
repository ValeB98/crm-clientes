<?php
// iniciar sesion para controlar acceso
session_start();
// si no hay usuario logueado o no es admin, redirigir a login y salir
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// crear conexion a la base de datos
$conexion = new mysqli("localhost", "root", "", "crm_clientes");
// si hay error en conexion, detener ejecucion con mensaje
if ($conexion->connect_error) { die("error de conexion: " . $conexion->connect_error); }

// obtener id del cliente desde parametro get
$id = $_GET['id'];
// consultar datos del cliente para mostrar en formulario
$cliente = $conexion->query("SELECT * FROM clientes WHERE id=$id")->fetch_assoc();

// si el formulario fue enviado (boton actualizar presionado)
if (isset($_POST['actualizar'])) {
    // obtener datos enviados del formulario
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $empresa = $_POST['empresa'];
    $ciudad = $_POST['ciudad'];

    // actualizar datos del cliente en la base de datos
    $conexion->query("UPDATE clientes SET 
        nombre='$nombre', 
        telefono='$telefono', 
        correo='$correo', 
        empresa='$empresa', 
        ciudad='$ciudad' 
        WHERE id=$id");

    // redirigir a dashboard para evitar reenviar formulario
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>editar cliente</title>
    <style>
        /* estilos basicos para centrar formulario y dar color oscuro */
        body { background:#121212; color:#fff; font-family:Arial; display:flex; justify-content:center; align-items:center; height:100vh; }
        form { background:#1e1e1e; padding:20px; border-radius:10px; width:400px; }
        label { display:block; margin-bottom:5px; }
        input { width:100%; padding:10px; margin-bottom:10px; border:none; border-radius:5px; background:#2a2a2a; color:#fff; }
        button { padding:10px; background:#4CAF50; border:none; color:#fff; width:100%; border-radius:5px; }
        button:hover { background:#45a049; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>editar cliente</h2>

        <!-- campo nombre, requerido -->
        <label>nombre:</label>
        <input type="text" name="nombre" value="<?= $cliente['nombre'] ?>" required>

        <!-- campo telefono -->
        <label>telefono:</label>
        <input type="text" name="telefono" value="<?= $cliente['telefono'] ?>">

        <!-- campo correo -->
        <label>correo:</label>
        <input type="email" name="correo" value="<?= $cliente['correo'] ?>">

        <!-- campo empresa -->
        <label>empresa:</label>
        <input type="text" name="empresa" value="<?= $cliente['empresa'] ?>">

        <!-- campo ciudad -->
        <label>ciudad:</label>
        <input type="text" name="ciudad" value="<?= $cliente['ciudad'] ?>">

        <!-- boton para enviar formulario -->
        <button type="submit" name="actualizar">actualizar</button>
    </form>
</body>
</html>