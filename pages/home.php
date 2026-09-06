<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

$baseUrl = $baseUrl ?? '';
$categorias = buscarCategorias($pdo);
$produtosRecentes = buscarProdutosRecentes($pdo, 8);

?>

<main class="container flex-grow-1 d-flex flex-column gap-5 py-3">
  <!-- Hero -->
  <section class="hero-section container-sm text-center pt-112 pb-5 pb-md-4">
    <div class="hero-content mx-auto d-flex flex-column gap-3">
      <h1 class="display-1">BELEZA QUE IMPRESSIONA</h1>
      <p class="hero-subtitle fw-light mb-4">Realce sua essência, cuide da sua pele e encontre o presente perfeito em um só lugar!</p>
      <a href="<?= $baseUrl ?>/produtos" class="btn btn-primary py-2 mx-auto rounded-5 px-5 fw-medium mt-3">Ver Todos os Produtos</a>
    </div>
  </section>

  <!-- Novidades (Vitrine em Destaque) -->
  <?php if (!empty($produtosRecentes)) : ?>
    <section class="novidades-section container-sm py-3">
      <div class="novidades-header d-flex flex-column flex-md-row justify-content-between align-items-center align-items-md-end text-center text-md-start gap-3 mb-4">
        <div>
          <h2 class="display-6 fw-bold text-dark mb-1">Novidades da ManuMake</h2>
          <p class="text-muted mb-0">Confira os últimos lançamentos selecionados para realçar a sua beleza</p>
        </div>
        <a href="<?= $baseUrl ?>/produtos" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium d-none d-md-inline-flex align-items-center gap-2">
          Ver Catálogo Completo <i class="bi bi-arrow-right"></i>
        </a>
      </div>

      <div class="row g-3 mx-auto items-grid">
        <?php foreach ($produtosRecentes as $produto) : ?>
          <article class="novidade-card col-6 p-0">
            <!-- Foto do produto com badge de novo -->
            <a href="<?= $baseUrl ?>/produtos/<?= $produto['id'] ?>" class="novidade-imagem-wrapper text-decoration-none">
              <span class="novidade-badge">NOVO</span>
              <img
                src="<?= $baseUrl ?>/uploads/<?= $produto['imagem'] ?>"
                alt="<?= e($produto['nome']) ?>"
                class="grid-item-imagem"
                loading="lazy"
                onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
            </a>

            <!-- Informações do produto -->
            <div class="novidade-corpo">
              <?php if (!empty($produto['categoria_nome'])) : ?>
                <span class="novidade-categoria"><?= e($produto['categoria_nome']) ?></span>
              <?php endif; ?>

              <!-- Nome do produto -->
              <a href="<?= $baseUrl ?>/produtos/<?= $produto['id'] ?>" class="novidade-titulo">
                <?= e($produto['nome']) ?>
              </a>

              <!-- Preço e Ação -->
              <div class="novidade-preco-container">
                <span class="novidade-preco">
                  <?= formatarPreco($produto['preco']) ?>
                </span>
                <a href="<?= $baseUrl ?>/produtos/<?= $produto['id'] ?>" class="btn btn-outline-primary novidade-btn">
                  <i class="bi bi-eye"></i> Ver Detalhes
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="text-center mt-4 d-md-none">
        <a href="<?= $baseUrl ?>/produtos" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-medium">
          Ver Catálogo Completo <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </section>
  <?php endif; ?>

  <!-- Categorias -->
  <?php if (!empty($categorias)) : ?>
    <section class="container-sm py-3">
      <div class="text-center mb-4">
        <h2 class="display-6 fw-bold text-dark mb-1">Compre por Categoria</h2>
        <p class="text-muted mb-0">Encontre os itens perfeitos para cada etapa da sua rotina</p>
      </div>

      <div class="row g-3 mx-auto items-grid">
        <?php foreach ($categorias as $categoria) : ?>
          <a href="<?= $baseUrl ?>/produtos?categoria=<?= $categoria['slug'] ?>" class="small categoria-item p-0 text-center col-6">
            <img
              src="<?= $baseUrl ?>/uploads/<?= $categoria['imagem'] ?>"
              alt="<?= e($categoria['nome']) ?>"
              class="grid-item-imagem"
              loading="lazy"
              onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
            <h6 class="w-100"><?= e($categoria['nome']) ?></h6>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</main>