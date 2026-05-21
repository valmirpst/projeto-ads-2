<div class="d-flex flex-column min-vh-100">

  <?php
  require_once 'includes/header.php';

  $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'home';

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