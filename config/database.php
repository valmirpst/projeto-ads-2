<?php

$config = require __DIR__ . '/env.php';

try {

  $dsn = sprintf(
    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
    $config['host'],
    $config['port'],
    $config['dbname'],
    $config['charset']
  );
  $pdo = new PDO(
    $dsn,
    $config['username'],
    $config['password']
  );

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {

  die("Erro na conexão: " . $e->getMessage());
}
