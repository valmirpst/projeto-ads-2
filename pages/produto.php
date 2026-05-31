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

$numeroTelefone = '5544999999999';
$mensagemWhatsapp = rawurlencode('Olá, tenho interesse no produto: ' . $produto['nome']);
$linkWhatsapp = "https://wa.me/$numeroTelefone?text={$mensagemWhatsapp}";

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos'],
  ['name' => $produto['nome'], 'href' => $baseUrl . '/produtos/' . $produto['id']],
];
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <div class="row g-md-5 align-items-start">
      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <img
          src="<?= $baseUrl ?>/uploads/<?= $produto['imagem'] ?>"
          alt="<?= $produto['nome'] ?>"
          class="img-fluid rounded"
          onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
      </div>
      <div class="col-12 col-md-6">
        <div class="d-flex flex-column gap-1_5 pt-xl-4">
          <badge class="small text-muted">Cuidados para a Pele</badge>
          <h2 class="mb-0"><?= $produto['nome'] ?></h2>
          <div class="fs-5">
            R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
          </div>
          <a
            href="<?= $linkWhatsapp ?>"
            class="btn btn-success btn-whatsapp mt-3 align-self-start d-inline-flex align-items-center gap-2 py-2 px-4 text-white fw-semibold rounded-pill w-100 text-center justify-content-center"
            target="_blank"
            rel="noopener noreferrer">
            <i class="bi bi-whatsapp"></i>
            Continuar no Whatsapp
          </a>
          <section class="mt-5 mt-md-4 pt-md-3">
            <h3 class="fw-light fs-4">Descrição</h3>

            <p class="mb-0 text-body-secondary lh-lg">
              <?= $produto['descricao'] ?>
            </p>
          </section>
        </div>
      </div>
    </div>
  </section>
</main>