<?php
session_start();

// Eliminar variables específicas de sesión
unset($_SESSION['usuario']);
unset($_SESSION['tipoUsuario']);
unset($_SESSION['idUsuario']);

// También puedes usar session_unset() si quieres eliminar todas las variables
// session_unset();

// Destruir la sesión completamente
session_destroy();

// Redireccionar al login o página de inicio pública
header("Location: ../login");
exit;
?>
