<?php

/**
 * Resolve os dados de SEO (título, descrição, imagem, URL canônica)
 * com base na página atual. Centraliza toda a lógica de metadados
 * que antes estava dispersa no index.php.
 *
 * @return array{title: string, description: string, image: string, url: string}
 */
function resolverSeo(PDO $pdo, ?string $pagina, ?int $produtoId, string $baseUrl): array
{
    $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host      = $_SERVER['HTTP_HOST'];

    $dados = [
        'title'       => 'ManuMake',
        'description' => 'Descubra os melhores produtos da ManuMake. Encontre itens exclusivos e qualidade garantida.',
        'image'       => $baseUrl . '/assets/images/ManuMakeLogoSemFundo.png',
        'url'         => $protocolo . '://' . $host . $_SERVER['REQUEST_URI'],
    ];

    if ($pagina === 'produto' && $produtoId !== null) {
        $produto = buscarProdutoPorId($pdo, $produtoId);

        if ($produto) {
            $dados['title']       = $produto['nome'] . ' - ManuMake';
            $dados['description'] = mb_strimwidth(strip_tags($produto['descricao']), 0, 160, '...');

            if (!empty($produto['imagem'])) {
                $dados['image'] = $baseUrl . '/uploads/' . $produto['imagem'];
            }
        }
    } elseif ($pagina === 'produtos') {
        $dados['title']       = 'Produtos - ManuMake';
        $dados['description'] = 'Navegue pelo nosso catálogo completo de maquiagens, cosméticos e cuidados pessoais.';
    } elseif ($pagina === 'sobre') {
        $dados['title']       = 'Sobre Nós - ManuMake';
        $dados['description'] = 'Conheça a história da ManuMake e nossa missão de realçar a sua beleza natural.';
    } elseif ($pagina === 'termos') {
        $dados['title']       = 'Termos de Uso - ManuMake';
        $dados['description'] = 'Termos e condições de uso do site e políticas da ManuMake.';
    }

    // Garante que a imagem tenha caminho absoluto para o Open Graph
    if (!preg_match('~^(?:f|ht)tps?://~i', $dados['image'])) {
        $dados['image'] = $protocolo . '://' . $host . $dados['image'];
    }

    return $dados;
}
