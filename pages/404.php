<?php
$baseUrl = $baseUrl ?? '';
$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Página não encontrada', 'href' => $baseUrl . '/404'],
];
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column justify-content-center gap-5">
  <div class="container-sm text-center">
    <h1>404</h1>
    <p>Página não encontrada.</p>
    <a href="<?= $baseUrl ?>/" class="btn btn-outline-secondary">Voltar para a página inicial</a>
  </div>
</main>