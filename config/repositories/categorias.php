<?php

function buscarCategorias(PDO $pdo): array
{
  $sql = "SELECT id, nome, slug, imagem FROM categoria ORDER BY ordem, nome";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function listarCategoriasAdmin(PDO $pdo): array
{
  $stmt = $pdo->prepare('SELECT id, nome, slug, ordem, imagem FROM categoria ORDER BY ordem, nome');
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function buscarCategoriaAdminPorId(PDO $pdo, int $id): ?array
{
  $stmt = $pdo->prepare('SELECT id, nome, slug, ordem, imagem FROM categoria WHERE id = :id');
  $stmt->execute([':id' => $id]);

  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function buscarImagemCategoria(PDO $pdo, int $id): ?string
{
  $stmt = $pdo->prepare('SELECT imagem FROM categoria WHERE id = :id');
  $stmt->execute([':id' => $id]);
  $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

  return $categoria['imagem'] ?? null;
}

function salvarCategoria(PDO $pdo, ?int $id, string $nome, string $slug, int $ordem, ?string $imagem): void
{
  if ($id) {
    $stmt = $pdo->prepare('UPDATE categoria SET nome = :nome, slug = :slug, ordem = :ordem, imagem = :imagem WHERE id = :id');
    $stmt->execute([
      ':nome' => $nome,
      ':slug' => $slug,
      ':ordem' => $ordem,
      ':imagem' => $imagem,
      ':id' => $id,
    ]);

    return;
  }

  $stmt = $pdo->prepare('INSERT INTO categoria (nome, slug, ordem, imagem) VALUES (:nome, :slug, :ordem, :imagem)');
  $stmt->execute([
    ':nome' => $nome,
    ':slug' => $slug,
    ':ordem' => $ordem,
    ':imagem' => $imagem,
  ]);
}

function excluirCategoria(PDO $pdo, int $id): void
{
  $stmt = $pdo->prepare('DELETE FROM categoria WHERE id = :id');
  $stmt->execute([':id' => $id]);
}
