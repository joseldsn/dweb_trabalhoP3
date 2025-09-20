<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

function current_user() {
  return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}
function require_login() {
  if (!current_user()) {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($script, '/public/') !== false) {
      header('Location: auth.php');
    } else {
      header('Location: ../public/auth.php');
    }
    exit;
  }
}
function is_admin() {
  $u = current_user(); return $u && ($u['role'] ?? '') === 'admin';
}
