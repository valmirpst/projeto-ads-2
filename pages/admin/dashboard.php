<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/functions.php';

$adminTitulo = 'Painel Administrativo - ManuMake';
?>

<?php require_once __DIR__ . '/../../includes/admin_head.php'; ?>

<main class="container py-5">
  <div class="d-flex flex-column justify-content-between align-items-start align-items-md-center gap-3 mb-5 border-bottom pb-4">
    <div>
      <h1 class="h2 fw-bold text-dark mb-1">Painel Administrativo</h1>
      <p class="text-muted mb-0">Gerencie produtos, categorias e acesse o site principal.</p>
    </div>
    <div class="d-flex justify-content-end gap-2 w-100 w-md-auto">
      <a class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-2 flex-grow-1 flex-md-grow-0 py-2 px-3" href="<?= e($baseUrl) ?>/">
        <i class="bi bi-globe"></i>
        <span>Voltar ao Site</span>
      </a>
      <a class="btn btn-outline-danger d-inline-flex align-items-center justify-content-center gap-2 flex-grow-1 flex-md-grow-0 py-2 px-3" href="<?= e($baseUrl) ?>/admin/logout">
        <i class="bi bi-box-arrow-right"></i>
        <span>Sair</span>
      </a>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-12 col-md-6">
      <a class="card border-0 shadow-sm text-decoration-none text-body h-100 position-relative overflow-hidden admin-card-hover" href="<?= e($baseUrl) ?>/admin/produtos" style="transition: transform 0.2s, box-shadow 0.2s;">
        <div class="card-body p-4 d-flex align-items-center gap-4">
          <div class="bg-primary bg-opacity-10 text-primary rounded p-3 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
            <i class="bi bi-bag fs-2"></i>
          </div>
          <div>
            <h2 class="h4 fw-semibold mb-1">Produtos</h2>
            <p class="text-muted mb-0">Cadastrar, editar e excluir produtos do catálogo.</p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-md-6">
      <a class="card border-0 shadow-sm text-decoration-none text-body h-100 position-relative overflow-hidden admin-card-hover" href="<?= e($baseUrl) ?>/admin/categorias" style="transition: transform 0.2s, box-shadow 0.2s;">
        <div class="card-body p-4 d-flex align-items-center gap-4">
          <div class="bg-success bg-opacity-10 text-success rounded p-3 d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
            <i class="bi bi-tags fs-2"></i>
          </div>
          <div>
            <h2 class="h4 fw-semibold mb-1">Categorias</h2>
            <p class="text-muted mb-0">Cadastrar, editar e excluir categorias de produtos.</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</main>

<style>
  .admin-card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .08) !important;
  }
</style>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>