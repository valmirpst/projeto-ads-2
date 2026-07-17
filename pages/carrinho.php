<?php

defined('APP_ROOT') || die('Acesso direto não permitido.');

$baseUrl = $baseUrl ?? '';

// ─── Tratamento de POST (PRG) ──────────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    switch ($acao) {

        case 'adicionar':
            $produtoId = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;
            $quantidade = isset($_POST['quantidade']) ? (int) $_POST['quantidade'] : 1;

            if ($produtoId <= 0) {
                definirMensagem('danger', 'Produto inválido.');
                header('Location: ' . $baseUrl . '/carrinho');
                exit;
            }

            $produto = buscarProdutoPorId($pdo, $produtoId);

            if (!$produto) {
                definirMensagem('danger', 'Produto não encontrado.');
                header('Location: ' . $baseUrl . '/carrinho');
                exit;
            }

            if ($quantidade < 1) {
                $quantidade = 1;
            }

            adicionarAoCarrinho($produto, $quantidade);
            definirMensagem('success', 'Produto adicionado ao carrinho!');
            header('Location: ' . $baseUrl . '/carrinho');
            exit;

        case 'atualizar':
            $produtoId = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;
            $quantidade = isset($_POST['quantidade']) ? (int) $_POST['quantidade'] : 0;

            if ($produtoId <= 0) {
                definirMensagem('danger', 'Produto inválido.');
                header('Location: ' . $baseUrl . '/carrinho');
                exit;
            }

            atualizarQuantidadeCarrinho($produtoId, $quantidade);

            if ($quantidade <= 0) {
                definirMensagem('info', 'Item removido do carrinho.');
            } else {
                definirMensagem('success', 'Quantidade atualizada.');
            }

            header('Location: ' . $baseUrl . '/carrinho');
            exit;

        case 'remover':
            $produtoId = isset($_POST['produto_id']) ? (int) $_POST['produto_id'] : 0;

            if ($produtoId <= 0) {
                definirMensagem('danger', 'Produto inválido.');
                header('Location: ' . $baseUrl . '/carrinho');
                exit;
            }

            removerDoCarrinho($produtoId);
            definirMensagem('info', 'Item removido do carrinho.');
            header('Location: ' . $baseUrl . '/carrinho');
            exit;

        case 'limpar':
            limparCarrinho();
            definirMensagem('info', 'Carrinho esvaziado.');
            header('Location: ' . $baseUrl . '/carrinho');
            exit;

        default:
            definirMensagem('danger', 'Ação desconhecida.');
            header('Location: ' . $baseUrl . '/carrinho');
            exit;
    }
}

// ─── GET — placeholder (interface será implementada na Etapa 2) ────────────

$breadcrumbs = [
    ['name' => 'Início', 'href' => $baseUrl . '/'],
    ['name' => 'Carrinho'],
];

$mensagem = obterMensagem();

require_once APP_ROOT . '/includes/breadcrumb.php';

if ($mensagem): ?>
    <div class="container my-3">
        <div class="alert alert-<?= e($mensagem['tipo']) ?> alert-dismissible fade show" role="alert">
            <?= e($mensagem['texto']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    </div>
<?php endif; ?>

<div class="container my-5 text-center">
    <i class="bi bi-cart3 fs-1 text-muted"></i>
    <h1 class="h4 mt-3 mb-2">Carrinho em construção</h1>
    <p class="text-muted">A interface completa será implementada na próxima etapa.</p>
    <a href="<?= e($baseUrl) ?>/" class="btn btn-primary mt-2">
        <i class="bi bi-arrow-left me-1"></i> Continuar comprando
    </a>
</div>