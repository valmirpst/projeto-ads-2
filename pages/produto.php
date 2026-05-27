<?php
require_once __DIR__ . '/../mock/produtos-data.php';
$baseUrl = $baseUrl ?? '';
$produtoId = $produtoId ?? null;
$produto = null;

foreach ($produtos as $item) {
  if ((int) $item['id'] === (int) $produtoId) {
    $produto = $item;
    break;
  }
}

if ($produto === null) {
  require_once __DIR__ . '/404.php';
  return;
}
?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <div class="row align-items-start">
      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <img
          src="<?= $baseUrl ?>/assets/images/<?= $produto['imagem'] ?>"
          alt="<?= $produto['nome'] ?>"
          class="img-fluid rounded"
          onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
      </div>
      <div class="col-12 col-md-6 d-flex flex-column gap-1_5">
        <badge class="small text-muted">Cuidados para a Pele</badge>
        <h2 class="mb-0"><?= $produto['nome'] ?></h2>
        <div class="fs-5">
          R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
        </div>
      </div>
    </div>
  </section>
</main>