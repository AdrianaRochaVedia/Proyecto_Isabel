<?php
// ------------------ Sesión ------------------
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ------------------ Rutas base ------------------
if (!defined('BASE_URL'))  define('BASE_URL', '/PROYECTO-SIS');
if (!defined('LOGIN_URL')) define('LOGIN_URL', BASE_URL . '/login/login.php');

function normalize_role(?string $r): string {
  return strtoupper(trim((string)$r));
}

function userRole(): string {
  return normalize_role($_SESSION['user_role'] ?? 'GUEST'); 
}

function isLoggedIn(): bool {
  return !empty($_SESSION['user_id']);
}
function isAdmin(): bool {
  $r = userRole();
  return in_array($r, ['ADMINISTRADOR'], true);
}
function redirect(string $url) {
  header('Location: ' . $url);
  exit;
}

function requireAuth() {
  if (isLoggedIn()) return;

  $current = $_SERVER['REQUEST_URI'] ?? '';
  if (strpos($current, LOGIN_URL) === false) {
    redirect(LOGIN_URL);
  }
}

function requireRole(array $roles) {
  requireAuth();

  $allowed = array_map('normalize_role', $roles);
  if (!in_array(userRole(), $allowed, true)) {
    http_response_code(403);
    echo "403 - No tienes permisos para ver esta página.";
    exit;
  }
}
