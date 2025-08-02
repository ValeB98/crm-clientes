<?php
// iniciar sesion para poder acceder a la sesion actual
session_start();

// destruir toda la sesion, eliminando todas las variables de sesion
session_destroy();

// redirigir al usuario a la pagina de login despues de cerrar sesion
header("Location: login.php");
exit();
?>