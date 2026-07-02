<?php

function caminhoUpload(string $nomeArquivo): string
{
  return __DIR__ . '/../../uploads/' . basename($nomeArquivo);
}

function removerImagemUpload(?string $imagem): void
{
  if (empty($imagem)) {
    return;
  }

  $caminho = caminhoUpload($imagem);

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
  $destino = caminhoUpload($nomeArquivo);

  if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
    throw new RuntimeException('Nao foi possivel salvar a imagem.');
  }

  if ($removerAtual) {
    removerImagemUpload($imagemAtual);
  }

  return $nomeArquivo;
}
