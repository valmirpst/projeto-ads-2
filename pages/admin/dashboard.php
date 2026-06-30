<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/functions.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Painel Administrativo - ManuMake</title>
  <link rel="icon" type="image/x-icon" href="<?= e($baseUrl) ?>/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
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
</body>

</html>
