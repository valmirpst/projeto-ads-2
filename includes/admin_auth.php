<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$baseUrl = $baseUrl ?? '';

if (empty($_SESSION['admin_usuario_id'])) {
  header('Location: ' . $baseUrl . '/admin/login');
  exit;
}
