<?php

/*
Este arquivo é para centralizar as consultas e funções relacionadas ao banco de dados e para evitar repetição.
Se achar que fiz com IA e não sei o que foi feito, é só me perguntar que eu explico! (Douglas disse: "se souber explicar tá valendo")
*/


require_once __DIR__ . '/../config/database.php';

function buscarCategorias(PDO $pdo): array
{
  $sql = "SELECT id, nome, slug, imagem FROM categoria ORDER BY ordem, nome";
  return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutos(PDO $pdo, ?string $categoriaSlug = null, ?string $ordenar = null): array
{
  // A lógica pode parecer complexa para a turma, mas Douglas falou que podia e é SÓ PEDIR QUE EU EXPLICO!
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

  $ordenacoes = [
    'preco-asc' => 'p.preco ASC',
    'preco-desc' => 'p.preco DESC',
    'nome-asc' => 'p.nome ASC',
    'nome-desc' => 'p.nome DESC',
  ];

  $sql .= " ORDER BY " . ($ordenacoes[$ordenar] ?? 'p.id DESC');

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarProdutosPorTermo(PDO $pdo, string $termo, ?string $categoriaSlug = null, ?string $ordenar = null): array
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

  $ordenacoes = [
    'preco-asc' => 'p.preco ASC',
    'preco-desc' => 'p.preco DESC',
    'nome-asc' => 'p.nome ASC',
    'nome-desc' => 'p.nome DESC',
  ];

  $sql .= " WHERE " . implode(" AND ", $where);
  $sql .= " ORDER BY CASE WHEN p.nome LIKE :termo_inicio THEN 0 ELSE 1 END, ";
  $sql .= $ordenacoes[$ordenar] ?? 'p.nome ASC';

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

// ---------------- 
// Pra cumprir rúbrica de tech forge: busca tudo do banco e faz um filtro em memória com php mesmo
// ----------------
function buscarCaracteristicasDoProduto(PDO $pdo, int $produtoId): array
{
  $sql = "
    SELECT c.id, c.nome, pc.produto_id
    FROM caracteristica c
    INNER JOIN produto_caracteristica pc ON pc.caracteristica_id = c.id
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $todas = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($todas)) {
    return [];
  }

  $filtradas = [];
  foreach ($todas as $caracteristica) {
    if ($caracteristica['produto_id'] == $produtoId) {
      $filtradas[] = [
        'id'   => $caracteristica['id'],
        'nome' => $caracteristica['nome'],
      ];
    }
  }

  return $filtradas;
}
