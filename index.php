<div class="d-flex flex-column min-vh-100">

  <?php
  $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';
  $stylesheet = $pagina . '.css';

  require_once 'includes/header.php';

  $rotas = [
    'home' => 'pages/home.php',
  ];

  if (array_key_exists($pagina, $rotas)) {
    require_once $rotas[$pagina];
  } else {
    require_once 'pages/404.php';
  }

  require_once 'includes/footer.php';
  ?>

</div>