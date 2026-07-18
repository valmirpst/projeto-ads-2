<?php

defined('APP_ROOT') || die('Acesso direto não permitido.');

$baseUrl = $baseUrl ?? '';

// ─── GET — interface completa ────────────────────────────────────────────────

$carrinho  = obterCarrinho();
$total     = calcularTotalCarrinho();
$mensagem  = obterMensagem();

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
      <a href="<?= e($baseUrl) ?>/produtos" class="btn btn-primary">
        <i class="bi bi-bag me-1"></i> Ver Produtos
      </a>
    </div>

  <?php else: ?>

    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th scope="col" colspan="2">Produto</th>
            <th scope="col" class="text-center">Preço Unit.</th>
            <th scope="col" class="text-center">Quantidade</th>
            <th scope="col" class="text-end">Subtotal</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($carrinho as $item): ?>
            <?php $subtotal = (float) $item['preco'] * (int) $item['quantidade']; ?>
            <tr>

              <!-- Imagem -->
              <td style="width: 80px;">
                <?php if (!empty($item['imagem'])): ?>
                  <img
                    src="<?= e($baseUrl) ?>/assets/images/<?= e($item['imagem']) ?>"
                    alt="<?= e($item['nome']) ?>"
                    class="carrinho-img">
                <?php else: ?>
                  <div class="carrinho-img bg-light d-flex align-items-center justify-content-center rounded">
                    <i class="bi bi-image text-muted fs-4"></i>
                  </div>
                <?php endif; ?>
              </td>

              <!-- Nome -->
              <td><?= e($item['nome']) ?></td>

              <!-- Preço unitário -->
              <td class="text-center text-muted">
                <?= e(formatarPreco($item['preco'])) ?>
              </td>

              <!-- Quantidade — formulário atualizar -->
              <td class="text-center">
                <form method="post" action="<?= e($baseUrl) ?>/carrinho" class="d-flex align-items-center justify-content-center gap-1">
                  <input type="hidden" name="acao" value="atualizar">
                  <input type="hidden" name="produto_id" value="<?= e($item['id']) ?>">
                  <input
                    type="number"
                    name="quantidade"
                    value="<?= e($item['quantidade']) ?>"
                    min="1"
                    max="999"
                    class="form-control form-control-sm carrinho-qty-input text-center"
                    aria-label="Quantidade de <?= e($item['nome']) ?>">
                  <button type="submit" class="btn btn-outline-secondary btn-sm" title="Atualizar quantidade">
                    <i class="bi bi-arrow-clockwise"></i>
                  </button>
                </form>
              </td>

              <!-- Subtotal -->
              <td class="text-end fw-semibold">
                <?= e(formatarPreco($subtotal)) ?>
              </td>

              <!-- Remover -->
              <td class="text-end">
                <form method="post" action="<?= e($baseUrl) ?>/carrinho">
                  <input type="hidden" name="acao" value="remover">
                  <input type="hidden" name="produto_id" value="<?= e($item['id']) ?>">
                  <button type="submit" class="btn btn-outline-danger btn-sm" title="Remover item">
                    <i class="bi bi-trash3"></i>
                  </button>
                </form>
              </td>

            </tr>
          <?php endforeach; ?>
        </tbody>

        <!-- Total geral -->
        <tfoot>
          <tr class="carrinho-total-row">
            <td colspan="4" class="text-end text-muted">Total:</td>
            <td class="text-end fw-bold fs-5"><?= e(formatarPreco($total)) ?></td>
            <td></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Ações do rodapé -->
    <div class="d-flex flex-wrap justify-content-between carrinho-actions mt-2">

      <form method="post" action="<?= e($baseUrl) ?>/carrinho">
        <input type="hidden" name="acao" value="limpar">
        <button type="submit" class="btn btn-outline-danger">
          <i class="bi bi-trash3 me-1"></i> Limpar Carrinho
        </button>
      </form>

      <a href="<?= e($baseUrl) ?>/produtos" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Continuar Comprando
      </a>

    </div>

  <?php endif; ?>

</div>