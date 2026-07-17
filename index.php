<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

define('APP_ROOT', __DIR__);

require_once 'config/database.php';
require_once 'config/functions.php';
require_once 'config/seo.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$base = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
$baseUrl = $base === '/' ? '' : $base;

if ($baseUrl !== '') {
  $path = preg_replace('#^' . preg_quote($baseUrl, '#') . '#', '', $path);
}

$path = trim($path, '/');
$segments = $path === '' ? [] : explode('/', $path);

$pagina = null;
$produtoId = null;
$adminPagina = null;

if (!empty($segments)) {
  $pagina = $segments[0];

  if ($pagina === 'admin') {
    $adminPagina = $segments[1] ?? 'dashboard';
  }

  if ($pagina === 'produtos' && isset($segments[1]) && ctype_digit($segments[1])) {
    $pagina = 'produto';
    $produtoId = (int) $segments[1];
  }
}

// Resolve metadados de SEO / Open Graph para a página atual
$seo = resolverSeo($pdo, $pagina, $produtoId, $baseUrl);
$seoTitle       = $seo['title'];
$seoDescription = $seo['description'];
$seoImage       = $seo['image'];
$seoUrl         = $seo['url'];

if ($adminPagina !== null) {
  $rotasAdmin = [
    'login' => 'pages/admin/login.php',
    'dashboard' => 'pages/admin/dashboard.php',
    'produtos' => 'pages/admin/produtos.php',
    'categorias' => 'pages/admin/categorias.php',
  ];

  if ($adminPagina === 'logout') {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
      $params = session_get_cookie_params();
      setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: ' . $baseUrl . '/admin/login');
    exit;
  }

  if (array_key_exists($adminPagina, $rotasAdmin)) {
    require_once $rotasAdmin[$adminPagina];
  } else {
    http_response_code(404);
    require_once 'pages/404.php';
  }

  exit;
}

if ($pagina === null || $pagina === '') {
  $pagina = 'home';
}

$stylesheet = $pagina . '.css';
?>

<div class="d-flex flex-column min-vh-100">

  <?php
  require_once 'includes/header.php';

  $rotas = [
    'home'      => 'pages/home.php',
    'produtos'  => 'pages/produtos.php',
    'produto'   => 'pages/produto.php',
    'sobre'     => 'pages/sobre.php',
    'termos'    => 'pages/termos.php',
    'carrinho'  => 'pages/carrinho.php',
  ];

  if ($pagina === 'produto' && $produtoId === null) {
    require_once 'pages/404.php';
  } elseif (array_key_exists($pagina, $rotas)) {
    require_once $rotas[$pagina];
  } else {
    require_once 'pages/404.php';
  }

  require_once 'includes/footer.php';
  ?>

</div>