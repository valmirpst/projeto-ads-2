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
    throw new RuntimeException('Não foi possível enviar a imagem.');
  }

  $extensao = strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
  $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

  if (!in_array($extensao, $permitidas, true)) {
    throw new RuntimeException('A imagem deve ser jpg, jpeg, png ou webp.');
  }

  $nomeArquivo = uniqid('', true) . '.' . $extensao;
  $destino = caminhoUpload($nomeArquivo);

  // Se a biblioteca GD estiver ativa, redimensiona e otimiza a imagem
  if (extension_loaded('gd')) {
    $tempPath = $_FILES[$campo]['tmp_name'];
    list($larguraOriginal, $alturaOriginal) = getimagesize($tempPath);

    if ($larguraOriginal > 0 && $alturaOriginal > 0) {
      // Carrega a imagem baseada na extensão
      $imagemOrigem = match ($extensao) {
        'jpg', 'jpeg' => @imagecreatefromjpeg($tempPath),
        'png' => @imagecreatefrompng($tempPath),
        'webp' => @imagecreatefromwebp($tempPath),
        default => null
      };

      if ($imagemOrigem) {
        // Define o limite de largura adequado para web (celulares e computadores)
        $larguraLimite = 1000;
        
        if ($larguraOriginal > $larguraLimite) {
          $novaLargura = $larguraLimite;
          $novaAltura = (int) (($alturaOriginal / $larguraOriginal) * $novaLargura);
        } else {
          $novaLargura = $larguraOriginal;
          $novaAltura = $alturaOriginal;
        }

        $imagemDestino = imagecreatetruecolor($novaLargura, $novaAltura);

        // Preserva transparência para PNG e WebP
        if ($extensao === 'png' || $extensao === 'webp') {
          imagealphablending($imagemDestino, false);
          imagesavealpha($imagemDestino, true);
        }

        // Redimensiona a imagem
        imagecopyresampled(
          $imagemDestino,
          $imagemOrigem,
          0, 0, 0, 0,
          $novaLargura,
          $novaAltura,
          $larguraOriginal,
          $alturaOriginal
        );

        // Salva com compressão otimizada
        $salvou = false;
        switch ($extensao) {
          case 'jpg':
          case 'jpeg':
            $salvou = imagejpeg($imagemDestino, $destino, 80); // Qualidade 80% (excelente compressão)
            break;
          case 'png':
            $salvou = imagepng($imagemDestino, $destino, 7); // Nível de compressão 7 de 9
            break;
          case 'webp':
            $salvou = imagewebp($imagemDestino, $destino, 80); // Qualidade 80%
            break;
        }

        if ($salvou) {
          if ($removerAtual) {
            removerImagemUpload($imagemAtual);
          }
          return $nomeArquivo;
        }
      }
    }
  }

  // Fallback seguro caso a biblioteca GD não esteja instalada ou falhe
  if (!move_uploaded_file($_FILES[$campo]['tmp_name'], $destino)) {
    throw new RuntimeException('Não foi possível salvar a imagem.');
  }

  if ($removerAtual) {
    removerImagemUpload($imagemAtual);
  }

  return $nomeArquivo;
}
