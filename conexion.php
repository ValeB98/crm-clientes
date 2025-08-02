<?php
// crear conexion a la base de datos (host, usuario, contraseña, nombre_bd)
$conexion = new mysqli("localhost", "root", "", "crm_clientes");

// verificar si hubo un error en la conexion
if ($conexion->connect_error) {
    // detener la ejecucion y mostrar mensaje de error con el detalle del error
    die("error de conexion: " . $conexion->connect_error);
}
?>