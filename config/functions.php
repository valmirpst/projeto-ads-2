<?php

require_once __DIR__ . '/../config/database.php';

function buscarCategorias(PDO $pdo): array
{
  $sql = "SELECT id, nome, slug, imagem FROM categoria";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutos(PDO $pdo, ?string $categoriaSlug = null): array
{
  $sql = "SELECT p.id, p.nome, p.preco, p.imagem FROM produto p";
  $params = [];

  if ($categoriaSlug) {
    $sql .= " JOIN categoria c ON p.categoria_id = c.id WHERE c.slug = :slug";
    $params[':slug'] = $categoriaSlug;
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutoPorId(PDO $pdo, int|null $id): array|null
{
  $sql = "SELECT p.id, p.nome, p.preco, p.descricao, p.imagem FROM produto p WHERE p.id = :id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':id' => $id]);
  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function buscarCaracteristicasDoProduto(PDO $pdo, int $produtoId): array
{
  $sql = "
    SELECT c.id, c.nome
    FROM caracteristica c
    INNER JOIN produto_caracteristica pc ON pc.caracteristica_id = c.id
    WHERE pc.produto_id = :produto_id
    ORDER BY c.nome
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([':produto_id' => $produtoId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
