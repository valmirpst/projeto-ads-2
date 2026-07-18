<?php

defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once APP_ROOT . '/config/config.php';

$baseUrl = $baseUrl ?? '';

// ─── GET — interface completa ────────────────────────────────────────────────

$carrinho  = obterCarrinho();
$total     = calcularTotalCarrinho();
$mensagem  = obterMensagem();

$linkWhatsapp = '#';
if (!empty($carrinho)) {
  $numeroTelefone = defined('WHATSAPP_NUMERO') ? WHATSAPP_NUMERO : '';

  $texto = "Olá! Gostaria de finalizar o seguinte pedido:\n\n";

  foreach ($carrinho as $item) {
    $subtotalItem = (float) $item['preco'] * (int) $item['quantidade'];
    $precoFormatado = formatarPreco($item['preco']);
    $subtotalFormatado = formatarPreco($subtotalItem);

    $texto .= "- *{$item['nome']}*\n";
    $texto .= "  {$item['quantidade']}x {$precoFormatado} = {$subtotalFormatado}\n";
  }

  $totalFormatado = formatarPreco($total);
  $texto .= "\n*Total do Pedido: {$totalFormatado}*";

  $mensagemWhatsapp = rawurlencode($texto);
  $linkWhatsapp = "https://wa.me/{$numeroTelefone}?text={$mensagemWhatsapp}";
}

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Carrinho'],
];

require_once APP_ROOT . '/includes/breadcrumb.php';
?>

<div class="container my-4">

  <?php if ($mensagem): ?>
    <div class="alert alert-<?= e($mensagem['tipo']) ?> alert-dismissible fade show" role="alert">
      <?= e($mensagem['texto']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  <?php endif; ?>

  <h1 class="h4 mb-4">
    <i class="bi bi-cart3 me-2"></i>Meu Carrinho
  </h1>

  <?php if (empty($carrinho)): ?>

    <div class="text-center py-5">
      <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
      <p class="text-muted mb-4">Seu carrinho está vazio.</p>
      <a href="<?= e($baseUrl) ?>/produtos" class="btn btn-primary rounded-5 px-5 fw-medium">
        <i class="bi bi-bag me-1"></i> Ver Produtos
      </a>
    </div>

  <?php else: ?>

    <div class="row g-4">
      <!-- Lista de Produtos -->
      <div class="col-lg-8">
        <?php foreach ($carrinho as $item): ?>
          <?php $subtotal = (float) $item['preco'] * (int) $item['quantidade']; ?>
          <div class="card mb-3 border-0 shadow-sm rounded-4 carrinho-card">
            <div class="card-body p-3 p-md-4">
              <div class="row align-items-center g-3">
                <!-- Imagem -->
                <div class="col-3 col-md-2 text-center">
                  <a href="<?= e($baseUrl) ?>/produtos/<?= e($item['id']) ?>">
                    <?php if (!empty($item['imagem'])): ?>
                      <img
                        src="<?= e($baseUrl) ?>/uploads/<?= e($item['imagem']) ?>"
                        alt="<?= e($item['nome']) ?>"
                        class="img-fluid rounded-3 carrinho-img-card"
                        onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                    <?php else: ?>
                      <div class="carrinho-img-card bg-light d-flex align-items-center justify-content-center rounded-3 mx-auto text-dark">
                        <i class="bi bi-image text-muted fs-4"></i>
                      </div>
                    <?php endif; ?>
                  </a>
                </div>

                <!-- Info do Produto -->
                <div class="col-9 col-md-4">
                  <h5 class="fs-6 fw-bold mb-1 text-truncate" title="<?= e($item['nome']) ?>">
                    <a href="<?= e($baseUrl) ?>/produtos/<?= e($item['id']) ?>" class="text-decoration-none text-dark">
                      <?= e($item['nome']) ?>
                    </a>
                  </h5>
                  <p class="text-muted small mb-0">Vendido e entregue por <strong>ManuMake</strong></p>
                </div>

                <!-- Controles e Preço -->
                <div class="col-12 col-md-6 mt-3 mt-md-0">
                  <div class="d-flex align-items-center justify-content-between justify-content-md-end gap-3 gap-md-4">

                    <!-- Quantidade -->
                    <form method="post" action="<?= e($baseUrl) ?>/carrinho" class="d-flex align-items-center">
                      <input type="hidden" name="acao" value="atualizar">
                      <input type="hidden" name="produto_id" value="<?= e($item['id']) ?>">
                      <div class="input-group input-group-sm carrinho-qty-group shadow-sm rounded-pill overflow-hidden">
                        <button type="submit" class="btn btn-light px-2 border-0 text-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" title="Diminuir">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input
                          type="number"
                          name="quantidade"
                          value="<?= e($item['quantidade']) ?>"
                          min="1"
                          max="999"
                          class="form-control border-0 text-center fw-semibold px-0 bg-light"
                          style="width: 45px; box-shadow: none;"
                          aria-label="Quantidade">
                        <button type="submit" class="btn btn-light px-2 border-0 text-secondary" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" title="Aumentar">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>
                    </form>

                    <!-- Preço -->
                    <div class="text-end">
                      <div class="fw-bold text-primary fs-5 lh-1 mb-1"><?= e(formatarPreco($subtotal)) ?></div>
                      <div class="text-muted small">un. <?= e(formatarPreco($item['preco'])) ?></div>
                    </div>

                    <!-- Remover -->
                    <form method="post" action="<?= e($baseUrl) ?>/carrinho">
                      <input type="hidden" name="acao" value="remover">
                      <input type="hidden" name="produto_id" value="<?= e($item['id']) ?>">
                      <button type="submit" class="btn btn-link text-danger p-0 ms-2" title="Remover item">
                        <i class="bi bi-trash3 fs-5"></i>
                      </button>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Resumo do Pedido -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 2rem;">
          <div class="card-body p-4">
            <h5 class="card-title fw-bold mb-4">Resumo do pedido</h5>

            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Subtotal (<?= array_sum(array_column($carrinho, 'quantidade')) ?> itens)</span>
              <span class="fw-medium"><?= e(formatarPreco($total)) ?></span>
            </div>

            <hr class="border-secondary-subtle mb-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
              <span class="fw-bold fs-5">Total</span>
              <span class="fw-bold fs-4 text-primary"><?= e(formatarPreco($total)) ?></span>
            </div>

            <!-- Ações -->
            <div class="d-flex flex-column gap-3">
              <a href="<?= e($linkWhatsapp) ?>" class="btn btn-success btn-whatsapp rounded-5 py-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-whatsapp"></i> Continuar
              </a>

              <a href="<?= e($baseUrl) ?>/produtos" class="btn btn-outline-primary rounded-5 py-2 fw-medium">
                Continuar Comprando
              </a>

              <form method="post" action="<?= e($baseUrl) ?>/carrinho" class="mt-2 text-center">
                <input type="hidden" name="acao" value="limpar">
                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 fw-medium">
                  <i class="bi bi-trash3 me-1"></i> Limpar Carrinho
                </button>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>

  <?php endif; ?>

</div>