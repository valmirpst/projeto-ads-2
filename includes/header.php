<?php
$baseUrl = $baseUrl ?? '';
$navLinks = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos'],
  ['name' => 'Sobre', 'href' => $baseUrl . '/sobre'],
];
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ManuMake</title>

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
        class=" navbar-toggler border-0 shadow-none"
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

    </nav>
    <!-- Navbar collapsed -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav px-4 py-1">
        <?php foreach ($navLinks as $link): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= $link['href'] ?>"><?= $link['name'] ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </header>