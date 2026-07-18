<?php

defined('APP_ROOT') || die('Acesso direto não permitido.');

// Este arquivo é incluído pelo index.php ANTES de qualquer output HTML.
// Processa apenas requisições POST do carrinho e redireciona (PRG).

$acao    = $_POST['acao'] ?? '';
$baseUrl = $baseUrl ?? '';

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

        // Retorna à página de origem se informada (ex.: página do produto)
        $redirect = $_POST['redirect'] ?? '';
        if ($redirect !== '' && str_starts_with($redirect, $baseUrl . '/')) {
            header('Location: ' . $redirect);
        } else {
            header('Location: ' . $baseUrl . '/carrinho');
        }
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
