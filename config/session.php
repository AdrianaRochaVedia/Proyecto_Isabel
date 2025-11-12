<?php
// config/session.php - Inicializar y verificar sesión

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function esAdmin() {
    return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function obtenerIdUsuario() {
    return $_SESSION['id'] ?? 1;
}

function obtenerNombreUsuario() {
    return ($_SESSION['nombre'] ?? 'Usuario') . ' ' . ($_SESSION['apellido'] ?? '');
}

function verificarSesion() {
    if (!isset($_SESSION['id'])) {
        header('Location: ../login/login.php');
        exit;
    }
}

function verificarAdmin() {
    verificarSesion();
    if (!esAdmin()) {
        header('Location: ../contenido/lecciones.php');
        exit;
    }
}
?>