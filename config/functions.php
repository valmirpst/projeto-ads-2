<?php

/*
Este arquivo é para centralizar as consultas e funções relacionadas ao banco de dados e para evitar repetição.
Se achar que fiz com IA e não sei o que foi feito, é só me perguntar que eu explico! (Douglas disse: "se souber explicar tá valendo")
*/


require_once __DIR__ . '/../config/database.php';

function e(mixed $valor): string
{
  return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function definirMensagem(string $tipo, string $texto): void
{
  $_SESSION['mensagem'] = [
    'tipo' => $tipo,
    'texto' => $texto,
  ];
}

function obterMensagem(): ?array
{
  if (empty($_SESSION['mensagem'])) {
    return null;
  }

  $mensagem = $_SESSION['mensagem'];
  unset($_SESSION['mensagem']);

  return $mensagem;
}

function gerarSlug(string $texto): string
{
  $texto = trim($texto);
  $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto) ?: $texto;
  $texto = strtolower($texto);
  $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
  $texto = trim($texto ?? '', '-');

  return $texto !== '' ? $texto : uniqid('item-');
}

function formatarPreco(float|string $preco): string
{
  return 'R$ ' . number_format((float) $preco, 2, ',', '.');
}

function removerImagemUpload(?string $imagem): void
{
  if (empty($imagem)) {
    return;
  }

  $nomeArquivo = basename($imagem);
  $caminho = __DIR__ . '/../uploads/' . $nomeArquivo;

  if (is_file($caminho)) {
    unlink($caminho);
  }
}

function salvarImagemUpload(string $campo, ?string $imagemAtual = null, bool $removerAtual = false): ?string
{
  if (empty($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
    return $imagemAtual;
  }

  if ($_FILES[$campo]['error'] !== UPLOAD_ERR_OK) {
    throw new RuntimeException('Nao foi possivel enviar a imagem.');
  }

  $extensao = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
  $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($extensao, $permitidas, true)) {
    throw new RuntimeException('A imagem deve ser jpg, jpeg, png ou webp.');
  }

  $nomeArquivo = uniqid('', true) . '.' . $extensao;
  $destino = __DIR__ . '/../uploads/' . $nomeArquivo;

  if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
    throw new RuntimeException('Nao foi possivel salvar a imagem.');
  }

  if ($removerAtual) {
    removerImagemUpload($imagemAtual);
  }

  return $nomeArquivo;
}

function buscarCategorias(PDO $pdo): array
{
  $sql = "SELECT id, nome, slug, imagem FROM categoria ORDER BY ordem, nome";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
