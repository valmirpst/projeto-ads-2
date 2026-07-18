<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

$baseUrl   = $baseUrl ?? '';
$produtoId = $produtoId ?? null;

$produto = buscarProdutoPorId($pdo, $produtoId);

if ($produto === null) {
  require_once __DIR__ . '/404.php';
  return;
}

$caracteristicas = buscarCaracteristicasDoProduto($pdo, (int) $produto['id']);

$protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$urlProduto = $protocolo . '://' . $host . $baseUrl . '/produtos/' . $produto['id'];
$numeroTelefone = WHATSAPP_NUMERO;
$mensagemWhatsapp = rawurlencode(
  "Olá, tenho interesse no produto: *{$produto['nome']}* ({$urlProduto})"
);
$linkWhatsapp = "https://wa.me/{$numeroTelefone}?text={$mensagemWhatsapp}";

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos'],
  ['name' => $produto['nome'], 'href' => $baseUrl . '/produtos/' . $produto['id']],
];
$mensagem = obterMensagem();
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<?php if ($mensagem): ?>
  <div class="container container-sm pt-3">
    <div class="alert alert-<?= e($mensagem['tipo']) ?> alert-dismissible fade show" role="alert">
      <?= e($mensagem['texto']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  </div>
<?php endif; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <div class="row g-md-5 align-items-start">
      <div class="col-12 col-md-6 mb-3 mb-md-0">
        <img
          src="<?= $baseUrl ?>/uploads/<?= $produto['imagem'] ?>"
          alt="<?= $produto['nome'] ?>"
          class="produto-imagem img-fluid rounded"
          loading="lazy"
          onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
      </div>
      <div class="col-12 col-md-6">
        <div class="d-flex flex-column gap-1_5 pt-xl-4">
          <badge class="small text-muted">Cuidados para a Pele</badge>
          <h2 class="mb-0"><?= $produto['nome'] ?></h2>
          <div class="fs-5">
            <?= formatarPreco($produto['preco']) ?>
          </div>
          <?php if (!empty($caracteristicas)) : ?>
            <div class="produto-caracteristicas d-flex flex-column gap-2 mt-2">
              <span class="small text-muted">Características</span>
              <div class="d-flex flex-wrap gap-2">
                <?php foreach ($caracteristicas as $caracteristica) : ?>
                  <span class="produto-caracteristica small">
                    <?= $caracteristica['nome'] ?>
                  </span>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
          <!-- Formulário: adicionar ao carrinho -->
          <form method="post" action="<?= e($baseUrl) ?>/carrinho" class="d-flex align-items-center gap-2 mt-3">
            <input type="hidden" name="acao" value="adicionar">
            <input type="hidden" name="produto_id" value="<?= e($produto['id']) ?>">
            <input type="hidden" name="redirect" value="<?= e($baseUrl) ?>/produtos/<?= e($produto['id']) ?>">
            <label for="quantidade-produto" class="visually-hidden">Quantidade</label>
            <input
              type="number"
              id="quantidade-produto"
              name="quantidade"
              value="1"
              min="1"
              max="999"
              class="form-control form-control" style="width: 80px;"
              aria-label="Quantidade">
            <button type="submit" class="btn btn-primary flex-grow-1">
              <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
            </button>
          </form>

          <a
            href="<?= e($linkWhatsapp) ?>"
            class="btn btn-success btn-whatsapp align-self-start d-inline-flex align-items-center gap-2 py-2 px-4 text-white fw-semibold rounded-pill w-100 text-center justify-content-center"
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

<!-- Dados Estruturados para SEO e AEO -->
<script type="application/ld+json">
  {
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": <?= json_encode($produto['nome']) ?>,
    "image": <?= json_encode($seoImage ?? ($protocolo . '://' . $host . $baseUrl . '/uploads/' . $produto['imagem'])) ?>,
    "description": <?= json_encode(strip_tags($produto['descricao'])) ?>,
    "offers": {
      "@type": "Offer",
      "url": <?= json_encode($urlProduto) ?>,
      "priceCurrency": "BRL",
      "price": <?= json_encode((float) $produto['preco']) ?>,
      "availability": "https://schema.org/InStock",
      "priceValidUntil": <?= json_encode(date('Y-12-31', strtotime('+1 year'))) ?>
    }
  }
</script>