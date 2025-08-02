<?php
// iniciar sesion para controlar acceso
session_start();
// verificar que haya un usuario logueado y que su rol sea empleado
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'empleado') {
    // si no es empleado o no esta logueado, redirigir a login y salir
    header("Location: login.php");
    exit();
}
// crear conexion a la base de datos
$conexion = new mysqli("localhost", "root", "", "crm_clientes");

// si se envio formulario por metodo post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // obtener id del proyecto y estado nuevo desde formulario
    $id = $_POST['proyecto_id'];
    $estado = $_POST['estado'];
    // actualizar estado del proyecto solo si pertenece al empleado logueado
    $conexion->query("UPDATE proyectos SET estado='$estado' WHERE id=$id AND empleado_id=".$_SESSION['usuario_id']);
}
// redirigir a dashboard para mostrar actualizaciones
header("Location: dashboard.php");
exit();
?>