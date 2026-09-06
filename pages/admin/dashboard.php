<?php
// Esta página é carregada pelo index.php (front controller).
defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once __DIR__ . '/../../includes/admin_auth.php';

$totalProdutos = contarTotalProdutos($pdo);
$totalCategorias = contarTotalCategorias($pdo);

$adminTitulo = 'Painel Administrativo - ManuMake';
$adminPagina = $adminPagina ?? 'dashboard';
?>

<?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

<main class="container py-5">
  <div class="mb-5 border-bottom pb-4">
    <h1 class="h2 fw-bold text-dark mb-1">Painel Administrativo</h1>
    <p class="text-muted mb-0">Gerencie produtos, categorias e acesse o site principal.</p>
  </div>

  <div class="row g-4">
    <div class="col-12 col-md-6">
      <a class="card border-0 shadow-sm text-decoration-none text-body h-100 position-relative overflow-hidden admin-card-hover" href="<?= e($baseUrl) ?>/admin/produtos">
        <div class="card-body p-4 d-flex flex-column justify-content-between">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 text-uppercase fw-semibold text-muted tracking-wide mb-0">Produtos</h2>
            <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
              <i class="bi bi-bag fs-5"></i>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex align-items-baseline gap-2">
              <span class="display-5 fw-bold text-dark"><?= $totalProdutos ?></span>
              <span class="text-muted small">cadastrados</span>
            </div>
            <p class="text-muted small mb-0">Gerencie catálogo, preços e imagens.</p>
          </div>
          <div class="pt-3 border-top d-flex align-items-center justify-content-between text-primary small fw-semibold">
            <span>Acessar produtos</span>
            <i class="bi bi-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-6">
      <a class="card border-0 shadow-sm text-decoration-none text-body h-100 position-relative overflow-hidden admin-card-hover" href="<?= e($baseUrl) ?>/admin/categorias">
        <div class="card-body p-4 d-flex flex-column justify-content-between">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 text-uppercase fw-semibold text-muted tracking-wide mb-0">Categorias</h2>
            <div class="bg-success bg-opacity-10 text-success rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
              <i class="bi bi-tags fs-5"></i>
            </div>
          </div>
          <div class="mb-3">
            <div class="d-flex align-items-baseline gap-2">
              <span class="display-5 fw-bold text-dark"><?= $totalCategorias ?></span>
              <span class="text-muted small">cadastradas</span>
            </div>
            <p class="text-muted small mb-0">Gerencie categorias e organização do catálogo.</p>
          </div>
          <div class="pt-3 border-top d-flex align-items-center justify-content-between text-success small fw-semibold">
            <span>Acessar categorias</span>
            <i class="bi bi-arrow-right"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
</main>

<style>
  .admin-card-hover {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .admin-card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .08) !important;
  }

  .admin-card-hover .bi-arrow-right {
    transition: transform 0.2s ease;
  }

  .admin-card-hover:hover .bi-arrow-right {
    transform: translateX(4px);
  }

  .tracking-wide {
    letter-spacing: 0.05em;
  }
</style>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>