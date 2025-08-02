<?php
// iniciar sesion para poder usar variables de sesion
session_start();

// verificar si el metodo de envio es post (formulario enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // conectar a la base de datos mysql con usuario root sin clave
    $conexion = new mysqli("localhost", "root", "", "crm_clientes");

    // obtener los datos enviados desde el formulario login
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // preparar una consulta segura para evitar inyecciones sql
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo=? AND contrasena=?");

    // vincular los parametros recibidos a la consulta preparada
    $stmt->bind_param("ss", $correo, $contrasena);

    // ejecutar la consulta en la base de datos
    $stmt->execute();

    // obtener el resultado de la consulta
    $resultado = $stmt->get_result();

    // obtener el primer registro encontrado como array asociativo
    $usuario = $resultado->fetch_assoc();

    // si existe un usuario que coincida con correo y contraseña
    if ($usuario) {
        // guardar datos del usuario en la sesion para mantener la sesion activa
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol'] = $usuario['rol'];

        // redirigir al dashboard despues del login exitoso
        header("Location: dashboard.php");
        exit();
    } else {
        // si no se encontro el usuario, guardar mensaje de error
        $error = "Correo o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- enlazar archivo css externo para estilos -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Iniciar sesión</h2>
    <!-- mostrar error si existe -->
    <?php if(isset($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <!-- formulario para ingresar correo y contraseña -->
    <form method="POST">
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>