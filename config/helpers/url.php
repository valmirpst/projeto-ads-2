<?php

/**
 * Monta a URL da página de produtos com os parâmetros fornecidos.
 * Remove parâmetros vazios ou nulos automaticamente.
 */
function montarUrlProdutos(string $baseUrl, array $params = []): string
{
    $params = array_filter($params, fn($valor) => $valor !== null && $valor !== '');
    $query = http_build_query($params);

    return $baseUrl . '/produtos' . ($query ? '?' . $query : '');
}
