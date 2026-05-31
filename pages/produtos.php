<?php
require_once __DIR__ . '/../mock/produtos-data.php';
$baseUrl = $baseUrl ?? '';

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos'],
];
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <h2 class="fw-light fs-3 mb-5">Todos os produtos</h2>

    <div class="w-100 d-flex flex-wrap justify-content-between align-items-center mb-3 lh-1 row-gap-2">
      <!-- <button
        class="btn btn-sm btn-outline-primary py-1 px-3 d-flex align-items-center gap-1_5"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#filtrosCollapse">
        <i class="bi bi-funnel" style="font-size: 0.875rem;"></i>
        <span>Filtros</span>
      </button> -->
      <div class="w-100 text-end small d-flex align-items-center justify-content-end gap-1_5">
        <label for="ordenar">Ordenar por</label>
        <select class=" border-0 rounded-0" id="ordenar">
          <option value="">Mais relevantes</option>
          <option value="preco-asc">Preço: menor para maior</option>
          <option value="preco-desc">Preço: maior para menor</option>
          <option value="nome-asc">Nome: A-Z</option>
          <option value="nome-desc">Nome: Z-A</option>
        </select>
      </div>
    </div>

    <div class="produtos-grid mx-auto">
      <?php foreach ($produtos as $produto) : ?>
        <div class="produto-item">
          <!-- Foto do produto -->
          <a href="<?= $baseUrl ?>/produtos/<?= $produto['id'] ?>" class="produto-imagem text-center">
            <img
              src="<?= $baseUrl ?>/uploads/<?= $produto['imagem'] ?>"
              alt="<?= $produto['nome'] ?>"
              class="grid-item-imagem"
              onerror="this.onerror=null;this.src='<?= $baseUrl ?>/assets/images/fallback.jpg';">
          </a>
          <div class="d-flex flex-column">
            <!-- Nome do produto -->
            <a href="<?= $baseUrl ?>/produtos/<?= $produto['id'] ?>" class="produto-nome text-body text-decoration-none">
              <?= $produto['nome'] ?>
            </a>
            <!-- Preço do produto -->
            <span>
              R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
            </span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>