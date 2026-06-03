<div class="d-flex flex-column min-vh-100">

  <?php
  $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
  $baseUrl = $base === '/' ? '' : $base;

  if ($baseUrl !== '') {
    $path = preg_replace('#^' . preg_quote($baseUrl, '#') . '#', '', $path);
  }

  $path = trim($path, '/');
  $segments = $path === '' ? [] : explode('/', $path);

  $pagina = null;
  $produtoId = null;

  if (!empty($segments)) {
    $pagina = $segments[0];

    if ($pagina === 'produtos' && isset($segments[1]) && ctype_digit($segments[1])) {
      $pagina = 'produto';
      $produtoId = (int) $segments[1];
    }
  }

  if ($pagina === null || $pagina === '') {
    $pagina = 'home';
  }

  $stylesheet = $pagina . '.css';

  require_once 'includes/header.php';

  $rotas = [
    'home' => 'pages/home.php',
    'produtos' => 'pages/produtos.php',
    'produto' => 'pages/produto.php',
    'sobre' => 'pages/sobre.php',
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