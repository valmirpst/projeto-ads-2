<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/functions.php';

$adminTitulo = 'Painel Administrativo - ManuMake';
?>

<?php require_once __DIR__ . '/../../includes/admin_head.php'; ?>

<main class="container py-5">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <h1 class="h3 mb-1">Painel Administrativo</h1>
      <p class="text-muted mb-0">Gerencie produtos e categorias da loja.</p>
    </div>
    <a class="btn btn-outline-danger" href="<?= e($baseUrl) ?>/admin/logout">
      <i class="bi bi-box-arrow-right"></i>
      Sair
    </a>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <a class="card text-decoration-none text-body h-100" href="<?= e($baseUrl) ?>/admin/produtos">
        <div class="card-body">
          <i class="bi bi-bag fs-2 text-primary"></i>
          <h2 class="h5 mt-3">Produtos</h2>
          <p class="text-muted mb-0">Cadastrar, editar e excluir produtos.</p>
        </div>
      </a>
    </div>
    <div class="col-md-6">
      <a class="card text-decoration-none text-body h-100" href="<?= e($baseUrl) ?>/admin/categorias">
        <div class="card-body">
          <i class="bi bi-tags fs-2 text-primary"></i>
          <h2 class="h5 mt-3">Categorias</h2>
          <p class="text-muted mb-0">Cadastrar, editar e excluir categorias.</p>
        </div>
      </a>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>