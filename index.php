<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once 'config/database.php';
require_once 'config/functions.php';

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

// Configurações de SEO padrão (Fallback)
$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$seoUrl = $protocolo . '://' . $host . $_SERVER['REQUEST_URI'];

$seoTitle = "ManuMake";
$seoDescription = "Descubra os melhores produtos da ManuMake. Encontre itens exclusivos e qualidade garantida.";
$seoImage = $baseUrl . '/assets/images/ManuMakeLogoSemFundo.png';

if ($pagina === 'produto' && $produtoId !== null) {
  $produtoSeo = buscarProdutoPorId($pdo, $produtoId);
  if ($produtoSeo) {
    $seoTitle = $produtoSeo['nome'] . " - ManuMake";
    $seoDescription = mb_strimwidth(strip_tags($produtoSeo['descricao']), 0, 160, "...");
    if (!empty($produtoSeo['imagem'])) {
      $seoImage = $baseUrl . '/uploads/' . $produtoSeo['imagem'];
    }
  }
} elseif ($pagina === 'produtos') {
  $seoTitle = "Produtos - ManuMake";
  $seoDescription = "Navegue pelo nosso catálogo completo de maquiagens, cosméticos e cuidados pessoais.";
} elseif ($pagina === 'sobre') {
  $seoTitle = "Sobre Nós - ManuMake";
  $seoDescription = "Conheça a história da ManuMake e nossa missão de realçar a sua beleza natural.";
} elseif ($pagina === 'termos') {
  $seoTitle = "Termos de Uso - ManuMake";
  $seoDescription = "Termos e condições de uso do site e políticas da ManuMake.";
}

// Garante que a imagem do OpenGraph tenha um caminho absoluto
if (!preg_match('~^(?:f|ht)tps?://~i', $seoImage)) {
  $seoImage = $protocolo . '://' . $host . $seoImage;
}

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
    'home' => 'pages/home.php',
    'produtos' => 'pages/produtos.php',
    'produto' => 'pages/produto.php',
    'sobre' => 'pages/sobre.php',
    'termos' => 'pages/termos.php',
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