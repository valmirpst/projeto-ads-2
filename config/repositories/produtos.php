<?php

function buscarProdutos(PDO $pdo, ?string $categoriaSlug = null, ?string $ordenar = null, int $limite = 0, int $offset = 0): array
{
  $sql = "SELECT p.id, p.nome, p.preco, p.imagem FROM produto p";
  $params = [];
  $where = [];

  if ($categoriaSlug) {
    $sql .= " JOIN categoria c ON p.categoria_id = c.id";
    $where[] = "c.slug = :slug";
    $params[':slug'] = $categoriaSlug;
  }

  if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
  }

  $sql .= " ORDER BY " . obterOrdenacaoProdutos($ordenar, 'p.id DESC');

  if ($limite > 0) {
    $sql .= " LIMIT :limite OFFSET :offset";
    $params[':limite'] = $limite;
    $params[':offset'] = $offset;
  }

  $stmt = $pdo->prepare($sql);

  if ($limite > 0) {
    $stmt->bindValue(':limite', $params[':limite'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', $params[':offset'], PDO::PARAM_INT);
    $filtros = array_filter($params, fn($k) => !in_array($k, [':limite', ':offset']), ARRAY_FILTER_USE_KEY);
    foreach ($filtros as $k => $v) {
      $stmt->bindValue($k, $v);
    }
    $stmt->execute();
  } else {
    $stmt->execute($params);
  }

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarProdutos(PDO $pdo, ?string $categoriaSlug = null): int
{
  $sql = "SELECT COUNT(*) FROM produto p";
  $params = [];
  $where = [];

  if ($categoriaSlug) {
    $sql .= " JOIN categoria c ON p.categoria_id = c.id";
    $where[] = "c.slug = :slug";
    $params[':slug'] = $categoriaSlug;
  }

  if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  return (int) $stmt->fetchColumn();
}

function buscarProdutosPorTermo(PDO $pdo, string $termo, ?string $categoriaSlug = null, ?string $ordenar = null, int $limite = 0, int $offset = 0): array
{
  $termo = trim($termo);

  if (strlen($termo) < 2) {
    return [];
  }

  $sql = "
    SELECT p.id, p.nome, p.preco, p.imagem
    FROM produto p
  ";
  $params = [
    ':termo' => '%' . $termo . '%',
    ':termo_inicio' => $termo . '%',
  ];
  $where = ["p.nome LIKE :termo"];

  if ($categoriaSlug) {
    $sql .= " JOIN categoria c ON p.categoria_id = c.id";
    $where[] = "c.slug = :slug";
    $params[':slug'] = $categoriaSlug;
  }

  $sql .= " WHERE " . implode(" AND ", $where);
  $sql .= " ORDER BY CASE WHEN p.nome LIKE :termo_inicio THEN 0 ELSE 1 END, ";
  $sql .= obterOrdenacaoProdutos($ordenar, 'p.nome ASC');

  if ($limite > 0) {
    $sql .= " LIMIT :limite OFFSET :offset";
  }

  $stmt = $pdo->prepare($sql);

  if ($limite > 0) {
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $k => $v) {
      $stmt->bindValue($k, $v);
    }
    $stmt->execute();
  } else {
    $stmt->execute($params);
  }

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarProdutosPorTermo(PDO $pdo, string $termo, ?string $categoriaSlug = null): int
{
  $termo = trim($termo);

  if (strlen($termo) < 2) {
    return 0;
  }

  $sql = "SELECT COUNT(*) FROM produto p";
  $params = [':termo' => '%' . $termo . '%'];
  $where = ["p.nome LIKE :termo"];

  if ($categoriaSlug) {
    $sql .= " JOIN categoria c ON p.categoria_id = c.id";
    $where[] = "c.slug = :slug";
    $params[':slug'] = $categoriaSlug;
  }

  $sql .= " WHERE " . implode(" AND ", $where);

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  return (int) $stmt->fetchColumn();
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
    SELECT c.id, c.nome, pc.produto_id
    FROM caracteristica c
    INNER JOIN produto_caracteristica pc ON pc.caracteristica_id = c.id
    WHERE pc.produto_id = :produto_id
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([':produto_id' => $produtoId]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function listarProdutosAdmin(PDO $pdo): array
{
  $stmt = $pdo->prepare('
    SELECT p.id, p.nome, p.preco, p.imagem, c.nome AS categoria_nome
    FROM produto p
    INNER JOIN categoria c ON c.id = p.categoria_id
    ORDER BY p.id DESC
  ');
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutoAdminPorId(PDO $pdo, int $id): ?array
{
  $stmt = $pdo->prepare('SELECT id, nome, descricao, preco, imagem, categoria_id FROM produto WHERE id = :id');
  $stmt->execute([':id' => $id]);

  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function buscarImagemProduto(PDO $pdo, int $id): ?string
{
  $stmt = $pdo->prepare('SELECT imagem FROM produto WHERE id = :id');
  $stmt->execute([':id' => $id]);
  $produto = $stmt->fetch(PDO::FETCH_ASSOC);

  return $produto['imagem'] ?? null;
}

function salvarProduto(PDO $pdo, ?int $id, string $nome, string $descricao, float|string $preco, int $categoriaId, ?string $imagem): void
{
  if ($id) {
    $stmt = $pdo->prepare('UPDATE produto SET nome = :nome, descricao = :descricao, preco = :preco, categoria_id = :categoria_id, imagem = :imagem WHERE id = :id');
    $stmt->execute([
      ':nome' => $nome,
      ':descricao' => $descricao,
      ':preco' => $preco,
      ':categoria_id' => $categoriaId,
      ':imagem' => $imagem,
      ':id' => $id,
    ]);

    return;
  }

  $stmt = $pdo->prepare('INSERT INTO produto (nome, descricao, preco, categoria_id, imagem) VALUES (:nome, :descricao, :preco, :categoria_id, :imagem)');
  $stmt->execute([
    ':nome' => $nome,
    ':descricao' => $descricao,
    ':preco' => $preco,
    ':categoria_id' => $categoriaId,
    ':imagem' => $imagem,
  ]);
}

function excluirProduto(PDO $pdo, int $id): void
{
  $stmt = $pdo->prepare('DELETE FROM produto_caracteristica WHERE produto_id = :id');
  $stmt->execute([':id' => $id]);

  $stmt = $pdo->prepare('DELETE FROM produto WHERE id = :id');
  $stmt->execute([':id' => $id]);
}

function obterOrdenacaoProdutos(?string $ordenar, string $padrao): string
{
  $ordenacoes = [
    'preco-asc' => 'p.preco ASC',
    'preco-desc' => 'p.preco DESC',
    'nome-asc' => 'p.nome ASC',
    'nome-desc' => 'p.nome DESC',
  ];

  return $ordenacoes[$ordenar] ?? $padrao;
}
