<?php
$baseUrl = $baseUrl ?? '';
$adminTitulo = $adminTitulo ?? 'Admin ManuMake';
$adminBodyClass = $adminBodyClass ?? 'bg-light';
$adminPagina = $adminPagina ?? '';
$estaAutenticado = !empty($_SESSION['admin_usuario_id']);

$adminNavLinks = [
  ['name' => 'Dashboard',  'href' => $baseUrl . '/admin/dashboard',  'page' => 'dashboard',  'icon' => 'bi-speedometer2'],
  ['name' => 'Produtos',   'href' => $baseUrl . '/admin/produtos',   'page' => 'produtos',   'icon' => 'bi-bag'],
  ['name' => 'Categorias', 'href' => $baseUrl . '/admin/categorias', 'page' => 'categorias', 'icon' => 'bi-tags'],
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($adminTitulo) ?></title>
  <link rel="icon" type="image/x-icon" href="<?= e($baseUrl) ?>/favicon.ico">
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

<body class="<?= e($adminBodyClass) ?>">

  <header class="header bg-white">

    <nav class="navbar navbar-expand-lg position-relative px-2 px-lg-4 py-3 border-bottom border-primary-lighter">

      <?php if ($estaAutenticado): ?>
        <!-- Hamburger (Mobile) -->
        <button
          class="navbar-toggler border-0 shadow-none d-lg-none"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#adminNavbar"
          aria-controls="adminNavbar"
          aria-expanded="false"
          aria-label="Expandir navegação"
          title="Expandir navegação">

          <i class="bi bi-list" style="font-size: 2.25rem;"></i>
        </button>
      <?php endif; ?>

      <!-- Logo -->
      <a class="navbar-brand position-absolute start-50 top-50 translate-middle m-0 p-0" href="<?= e($baseUrl) ?><?= $estaAutenticado ? '/admin/dashboard' : '/' ?>">
        <img src="<?= e($baseUrl) ?>/assets/images/ManuMakeLogoSemFundo.png" alt="ManuMake Logo" width="128" height="64">
      </a>

      <?php if ($estaAutenticado): ?>
        <!-- Links Desktop -->
        <ul class="navbar-nav d-none d-lg-flex flex-row gap-3 gap-xl-4">
          <?php foreach ($adminNavLinks as $link): ?>
            <li class="nav-item">
              <a
                class="nav-link <?= $adminPagina === $link['page'] ? 'active' : '' ?>"
                href="<?= e($link['href']) ?>">
                <?= e($link['name']) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <!-- Ações Desktop (Direita) -->
        <div class="d-none d-lg-flex ms-auto my-auto align-items-center gap-2 gap-xl-3">
          <?php if (!empty($_SESSION['admin_usuario'])): ?>
            <span class="navbar-text me-lg-1 small text-muted">
              <i class="bi bi-person-circle me-1"></i>Olá, <strong class="text-dark"><?= e($_SESSION['admin_usuario']) ?></strong>
            </span>
          <?php endif; ?>
          <a class="btn btn-outline-primary btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1" href="<?= e($baseUrl) ?>/" target="_blank" title="Visualizar a loja em nova aba">
            <i class="bi bi-globe"></i>
            <span>Ver Site</span>
          </a>
          <a class="btn btn-outline-danger btn-sm rounded-pill px-3 d-inline-flex align-items-center gap-1" href="<?= e($baseUrl) ?>/admin/logout" title="Encerrar sessão">
            <i class="bi bi-box-arrow-right"></i>
            <span>Sair</span>
          </a>
        </div>

        <!-- Botão rápido Ver Site (Mobile - Direita) -->
        <a href="<?= e($baseUrl) ?>/" class="btn btn-outline-primary position-relative ms-auto me-2 rounded-circle d-inline-flex align-items-center justify-content-center d-lg-none" style="width: 40px; height: 40px;" aria-label="Ver Site" title="Ver Site">
          <i class="bi bi-globe" style="font-size: 1.1rem;"></i>
        </a>
      <?php else: ?>
        <a href="<?= e($baseUrl) ?>/" class="btn btn-outline-primary position-relative ms-auto me-2 rounded-circle d-inline-flex align-items-center justify-content-center d-lg-none" style="width: 40px; height: 40px;" aria-label="Ver Site" title="Ver Site">
          <i class="bi bi-globe" style="font-size: 1.1rem;"></i>
        </a>
        <a class="btn btn-outline-primary btn-sm ms-auto rounded-pill px-3 d-none d-lg-inline-flex align-items-center gap-1" href="<?= e($baseUrl) ?>/">
          <i class="bi bi-globe"></i>
          <span>Ver Site</span>
        </a>
      <?php endif; ?>

    </nav>

    <?php if ($estaAutenticado): ?>
      <!-- Mobile Menu Collapse -->
      <div class="collapse navbar-collapse d-lg-none" id="adminNavbar">
        <ul class="navbar-nav px-4 pt-2 pb-2">
          <?php foreach ($adminNavLinks as $link): ?>
            <li class="nav-item">
              <a
                class="nav-link d-flex align-items-center gap-2 <?= $adminPagina === $link['page'] ? 'active' : '' ?>"
                href="<?= e($link['href']) ?>">
                <i class="bi <?= $link['icon'] ?>"></i> <?= e($link['name']) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>

        <div class="px-4 pb-3 pt-2 border-top border-light-subtle d-flex flex-column gap-2">
          <?php if (!empty($_SESSION['admin_usuario'])): ?>
            <div class="d-flex align-items-center gap-2 text-muted small py-1">
              <i class="bi bi-person-circle fs-5 text-primary"></i>
              <span>Conectado como <strong class="text-dark"><?= e($_SESSION['admin_usuario']) ?></strong></span>
            </div>
          <?php endif; ?>
          <div class="d-flex gap-2">
            <a class="btn btn-outline-primary btn-sm flex-fill d-inline-flex align-items-center justify-content-center gap-1 py-2" href="<?= e($baseUrl) ?>/" target="_blank">
              <i class="bi bi-globe"></i> Ver Site
            </a>
            <a class="btn btn-outline-danger btn-sm flex-fill d-inline-flex align-items-center justify-content-center gap-1 py-2" href="<?= e($baseUrl) ?>/admin/logout">
              <i class="bi bi-box-arrow-right"></i> Sair
            </a>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </header>