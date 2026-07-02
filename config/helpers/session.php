<?php

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
