<?php
$baseUrl = $baseUrl ?? '';
$currentPage = $pagina ?? 'home';
$buscaHeader = trim($_GET['busca'] ?? '');

$navLinks = [
  ['name' => 'Início', 'href' => $baseUrl . '/', 'page' => 'home'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos', 'page' => 'produtos'],
  ['name' => 'Sobre', 'href' => $baseUrl . '/sobre', 'page' => 'sobre'],
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($seoTitle ?? 'ManuMake') ?></title>

  <meta name="description" content="<?= htmlspecialchars($seoDescription ?? 'Descubra os melhores produtos da ManuMake. Encontre itens exclusivos e qualidade garantida.') ?>">
  <meta name="author" content="ManuMake">
  <meta name="robots" content="index, follow">

  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($seoTitle ?? 'ManuMake') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($seoDescription ?? 'Descubra os melhores produtos da ManuMake. Encontre itens exclusivos e qualidade garantida.') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($seoImage ?? ($baseUrl . '/assets/images/ManuMakeLogoSemFundo.png')) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($seoUrl ?? '') ?>">
  <meta property="og:locale" content="pt_BR">

  <link rel="icon" type="image/x-icon" href="<?= $baseUrl ?>/favicon.ico">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/custom.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/header.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/footer.css">

  <!-- CSS dinâmico de cada página -->
  <?php
  if (!empty($stylesheet)) {
    $pageCssPath = "assets/css/pages/$stylesheet";

    if (file_exists($pageCssPath)) {
      echo "<link rel='stylesheet' href='" . $baseUrl . "/$pageCssPath'>";
    }
  }
  ?>
</head>

<body>

  <header class="header">

    <nav class="navbar navbar-expand-lg position-relative px-2 px-lg-4 py-3 border-bottom border-primary-lighter">

      <!-- Hamburger -->
      <button
        class="navbar-toggler border-0 shadow-none d-lg-none"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Expandir navegação"
        title="Expandir navegação">

        <i class="bi bi-list" style="font-size: 2.25rem;"></i>
      </button>

      <!-- Logo -->
      <a class="navbar-brand position-absolute start-50 translate-middle-x m-0" href="<?= $baseUrl ?>/">
        <img src="<?= $baseUrl ?>/assets/images/ManuMakeLogoSemFundo.png" alt="Logo" width="128" height="64">
      </a>

      <!-- Links Desktop -->
      <ul class="navbar-nav d-none d-lg-flex flex-row gap-4">
        <?php foreach ($navLinks as $link): ?>
          <li class="nav-item">
            <a
              class="nav-link <?= $currentPage === $link['page'] ? 'active' : '' ?>"
              href="<?= $link['href'] ?>">
              <?= $link['name'] ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <form class="header-busca d-none d-lg-flex ms-auto" method="get" action="<?= $baseUrl ?>/produtos">
        <label for="busca-header-desktop" class="visually-hidden">Buscar produtos</label>
        <input
          type="search"
          class="form-control"
          id="busca-header-desktop"
          name="busca"
          minlength="2"
          value="<?= htmlspecialchars($buscaHeader) ?>"
          placeholder="Buscar produtos"
          aria-label="Buscar produtos">
        <button class="btn btn-primary" type="submit" aria-label="Buscar">
          <i class="bi bi-search" aria-hidden="true"></i>
        </button>
      </form>

    </nav>

    <!-- Mobile -->
    <div class="collapse navbar-collapse d-lg-none" id="navbarSupportedContent">
      <ul class="navbar-nav px-4 pt-1 pb-3">

        <?php foreach ($navLinks as $link): ?>
          <li class="nav-item">
            <a
              class="nav-link <?= $currentPage === $link['page'] ? 'active' : '' ?>"
              href="<?= $link['href'] ?>">
              <?= $link['name'] ?>
            </a>
          </li>
        <?php endforeach; ?>

      </ul>

      <form class="header-busca header-busca-mobile px-4 pb-3" method="get" action="<?= $baseUrl ?>/produtos">
        <label for="busca-header-mobile" class="visually-hidden">Buscar produtos</label>
        <input
          type="search"
          class="form-control"
          id="busca-header-mobile"
          name="busca"
          minlength="2"
          value="<?= htmlspecialchars($buscaHeader) ?>"
          placeholder="Buscar produtos"
          aria-label="Buscar produtos">
        <button class="btn btn-primary" type="submit" aria-label="Buscar">
          <i class="bi bi-search" aria-hidden="true"></i>
        </button>
      </form>
    </div>

  </header>