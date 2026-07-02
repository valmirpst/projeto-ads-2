<?php

function e(mixed $valor): string
{
  return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function formatarPreco(float|string $preco): string
{
  return 'R$ ' . number_format((float) $preco, 2, ',', '.');
}
