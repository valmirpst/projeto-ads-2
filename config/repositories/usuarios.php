<?php

function buscarUsuarioPorLogin(PDO $pdo, string $usuario): ?array
{
  $stmt = $pdo->prepare('SELECT id, usuario, senha FROM usuario WHERE usuario = :usuario LIMIT 1');
  $stmt->execute([':usuario' => $usuario]);

  return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}
