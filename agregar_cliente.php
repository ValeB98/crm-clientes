<?php
// iniciar sesion o continuar con la sesion que ya inicio
session_start();

// verificar si el rol guardado en sesion no es admin
if ($_SESSION['rol'] !== 'admin') {
    // si no es admin, redirigir al dashboard y terminar ejecucion
    header("Location: dashboard.php");
    exit();
}

// comprobar si el metodo del formulario es POST (se envio un formulario)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // crear conexion a la base de datos (host, usuario, contraseña, bd)
    $conexion = new mysqli("localhost", "root", "", "crm_clientes");

    // preparar consulta sql para insertar un nuevo cliente
    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, correo, telefono, empresa, ciudad) VALUES (?,?,?,?,?)");

    // enlazar parametros del formulario con la consulta sql, todos son strings (sssss)
    $stmt->bind_param("sssss", $_POST['nombre'], $_POST['correo'], $_POST['telefono'], $_POST['empresa'], $_POST['ciudad']);

    // ejecutar la consulta preparada
    $stmt->execute();

    // redirigir al dashboard despues de guardar y terminar ejecucion
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- definir tipo de codificacion para caracteres -->
    <meta charset="UTF-8">
    <title>Agregar Cliente</title>
    <!-- enlace a los estilos -->
    <link rel="stylesheet" href="style.css">
    <style>
        /* estilos para las etiquetas label */
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        } 
    </style>
</head>
<body>
<div class="form-container">
<h2>Agregar Cliente</h2>
<form method="POST">
    <!-- etiqueta y campo de texto para nombre -->
    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" required>

    <!-- etiqueta y campo para correo electronico -->
    <label for="correo">Correo</label>
    <input type="email" name="correo" id="correo">

    <!-- etiqueta y campo para telefono -->
    <label for="telefono">Telefono</label>
    <input type="text" name="telefono" id="telefono">

    <!-- etiqueta y campo para empresa -->
    <label for="empresa">Empresa</label>
    <input type="text" name="empresa" id="empresa">

    <!-- etiqueta y campo para ciudad -->
    <label for="ciudad">Ciudad</label>
    <input type="text" name="ciudad" id="ciudad">

    <!-- boton para enviar formulario -->
    <button type="submit">Guardar</button>
</form>
</div>
</body>
</html>