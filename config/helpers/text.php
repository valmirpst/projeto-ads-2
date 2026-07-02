<?php

function gerarSlug(string $texto): string
{
  $texto = trim($texto);
  $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto) ?: $texto;
  $texto = strtolower($texto);
  $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
  $texto = trim($texto ?? '', '-');

  return $texto !== '' ? $texto : uniqid('item-');
}
