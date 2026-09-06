<?php

function buscarCaracteristicas(PDO $pdo): array
{
  $stmt = $pdo->prepare('SELECT id, nome FROM caracteristica ORDER BY nome');
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function contarTotalCaracteristicas(PDO $pdo): int
{
  $stmt = $pdo->query('SELECT COUNT(*) FROM caracteristica');

  return (int) $stmt->fetchColumn();
}

function buscarCaracteristicaAdminPorId(PDO $pdo, int $id): ?array
{
  $stmt = $pdo->prepare('SELECT id, nome FROM caracteristica WHERE id = :id');
  $stmt->execute([':id' => $id]);

  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function salvarCaracteristica(PDO $pdo, ?int $id, string $nome): void
{
  if ($id) {
    $stmt = $pdo->prepare('UPDATE caracteristica SET nome = :nome WHERE id = :id');
    $stmt->execute([
      ':nome' => $nome,
      ':id' => $id,
    ]);

    return;
  }

  $stmt = $pdo->prepare('INSERT INTO caracteristica (nome) VALUES (:nome)');
  $stmt->execute([
    ':nome' => $nome,
  ]);
}

function excluirCaracteristica(PDO $pdo, int $id): void
{
  $stmt = $pdo->prepare('DELETE FROM produto_caracteristica WHERE caracteristica_id = :id');
  $stmt->execute([':id' => $id]);

  $stmt = $pdo->prepare('DELETE FROM caracteristica WHERE id = :id');
  $stmt->execute([':id' => $id]);
}
