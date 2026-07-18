<?php

defined('APP_ROOT') || die('Acesso direto não permitido.');

/**
 * Retorna o carrinho atual da sessão.
 * Garante que a chave 'carrinho' exista como array.
 *
 * @return array<int, array{id:int,nome:string,preco:float,imagem:string|null,quantidade:int}>
 */
function obterCarrinho(): array
{
    if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    return $_SESSION['carrinho'];
}

/**
 * Adiciona um produto ao carrinho ou incrementa sua quantidade se já existir.
 *
 * @param array{id:int,nome:string,preco:float,imagem:string|null} $produto
 * @param int $quantidade
 */
function adicionarAoCarrinho(array $produto, int $quantidade = 1): void
{
    obterCarrinho(); // garante que $_SESSION['carrinho'] existe

    $id = (int) $produto['id'];

    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade'] += $quantidade;
    } else {
        $_SESSION['carrinho'][$id] = [
            'id'        => $id,
            'nome'      => $produto['nome'],
            'preco'     => (float) $produto['preco'],
            'imagem'    => $produto['imagem'] ?? null,
            'quantidade' => $quantidade,
        ];
    }
}

/**
 * Atualiza a quantidade de um item no carrinho.
 * Se a quantidade for <= 0, remove o item.
 *
 * @param int $produtoId
 * @param int $quantidade
 */
function atualizarQuantidadeCarrinho(int $produtoId, int $quantidade): void
{
    obterCarrinho();

    if ($quantidade <= 0) {
        removerDoCarrinho($produtoId);
        return;
    }

    if (isset($_SESSION['carrinho'][$produtoId])) {
        $_SESSION['carrinho'][$produtoId]['quantidade'] = $quantidade;
    }
}

/**
 * Remove um item do carrinho pelo ID do produto.
 *
 * @param int $produtoId
 */
function removerDoCarrinho(int $produtoId): void
{
    obterCarrinho();
    unset($_SESSION['carrinho'][$produtoId]);
}

/**
 * Remove todos os itens do carrinho.
 */
function limparCarrinho(): void
{
    $_SESSION['carrinho'] = [];
}

/**
 * Calcula o valor total do carrinho.
 *
 * @return float
 */
function calcularTotalCarrinho(): float
{
    $carrinho = obterCarrinho();
    $total = 0.0;

    foreach ($carrinho as $item) {
        $total += (float) $item['preco'] * (int) $item['quantidade'];
    }

    return $total;
}

/**
 * Retorna o número total de itens (unidades) no carrinho.
 *
 * @return int
 */
function contarItensCarrinho(): int
{
    $carrinho = obterCarrinho();
    $total = 0;

    foreach ($carrinho as $item) {
        $total += (int) $item['quantidade'];
    }

    return $total;
}
