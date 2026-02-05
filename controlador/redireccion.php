<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que exista sesión de usuario
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index");
    exit;
}
?>
