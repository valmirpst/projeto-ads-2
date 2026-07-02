<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/functions.php';

$baseUrl = $baseUrl ?? '';

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Produtos', 'href' => $baseUrl . '/produtos'],
];

function montarUrlProdutos(string $baseUrl, array $params = []): string
{
  $params = array_filter($params, fn($valor) => $valor !== null && $valor !== '');
  $query = http_build_query($params);

  return $baseUrl . '/produtos' . ($query ? '?' . $query : '');
}

$categoriaSlug = $_GET['categoria'] ?? null;
$ordenar = $_GET['ordenar'] ?? '';
$busca = trim($_GET['busca'] ?? '');
$buscaAtiva = $busca !== '';
$categorias = buscarCategorias($pdo);
$produtos = $buscaAtiva
  ? buscarProdutosPorTermo($pdo, $busca, $categoriaSlug, $ordenar)
  : buscarProdutos($pdo, $categoriaSlug, $ordenar);
$totalFiltrosAtivos = ($categoriaSlug ? 1 : 0) + ($buscaAtiva ? 1 : 0);
$filtrosAtivos = $totalFiltrosAtivos > 0;
$filtrosCompartilhados = [
  'ordenar' => $ordenar,
  'busca' => $busca,
];

?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <h2 class="fw-light fs-3 mb-5">Todos os produtos</h2>

    <div class="w-100 d-flex justify-content-between align-items-center mb-3 lh-1 row-gap-2 column-gap-1">
      <button
        class="d-flex d-lg-none btn btn-sm btn-outline-primary py-1 px-3 align-items-center gap-1_5"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#filtrosOffcanvas"
        aria-controls="filtrosOffcanvas"
        aria-expanded="false">
        <i class="bi bi-funnel" style="font-size: 0.875rem;"></i>
        <span>Filtros</span>
        <?php if ($filtrosAtivos) : ?>
          <span class="badge rounded-pill text-bg-primary filtros-badge" aria-label="<?= $totalFiltrosAtivos ?> filtro ativo">
            <?= $totalFiltrosAtivos ?>
          </span>
        <?php endif; ?>
      </button>

      <form class="ms-auto small d-flex flex-wrap align-items-center gap-0_5 column-gap-1 mb-0" method="get">
        <?php if ($categoriaSlug) : ?>
          <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoriaSlug) ?>">
        <?php endif; ?>
        <?php if ($buscaAtiva) : ?>
          <input type="hidden" name="busca" value="<?= htmlspecialchars($busca) ?>">
        <?php endif; ?>
        <div class="ordenar-filtro-container d-flex flex-column align-items-sm-center flex-sm-row ms-auto gap-0_5 column-gap-1">
          <label for="ordenar" class="ps-1 fw-light">Ordenar por</label>
          <select class="border-0 rounded-0" id="ordenar" name="ordenar" onchange="this.form.submit()">
            <option value="">Mais relevantes</option>
            <option value="preco-asc" <?= $ordenar === 'preco-asc' ? 'selected' : '' ?>>Preço: menor para maior</option>
            <option value="preco-desc" <?= $ordenar === 'preco-desc' ? 'selected' : '' ?>>Preço: maior para menor</option>
            <option value="nome-asc" <?= $ordenar === 'nome-asc' ? 'selected' : '' ?>>Nome: A-Z</option>
            <option value="nome-desc" <?= $ordenar === 'nome-desc' ? 'selected' : '' ?>>Nome: Z-A</option>
          </select>
        </div>
      </form>
    </div>

    <div class="row g-4 align-items-start">
      <aside class="col-lg-3 order-lg-first">
        <div
          class="offcanvas-lg offcanvas-start filtros-offcanvas"
          tabindex="-1"
          id="filtrosOffcanvas"
          aria-labelledby="filtrosOffcanvasLabel">
          <div class="offcanvas-header d-lg-none border-bottom">
            <h3 class="fs-6 fw-medium mb-0" id="filtrosOffcanvasLabel">Filtros</h3>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="offcanvas"
              data-bs-target="#filtrosOffcanvas"
              aria-label="Fechar filtros"></button>
          </div>
          <div class="offcanvas-body p-0 d-block">
            <div class="filtros-sidebar p-3">
              <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                <h3 class="fs-6 fw-medium mb-0 d-none d-lg-block">Filtros</h3>
                <?php if ($filtrosAtivos) : ?>
                  <a href="<?= $baseUrl ?>/produtos" class="small link-body-emphasis">Limpar</a>
                <?php endif; ?>
              </div>

              <div class="filtro-grupo">
                <h4 class="filtro-titulo">Categorias</h4>
                <div class="d-flex flex-column gap-2">
                  <a
                    href="<?= montarUrlProdutos($baseUrl, $filtrosCompartilhados) ?>"
                    class="filtro-link <?= !$categoriaSlug ? 'active' : '' ?>">
                    Todas
                  </a>
                  <?php foreach ($categorias as $categoria) : ?>
                    <a
                      href="<?= montarUrlProdutos($baseUrl, array_merge($filtrosCompartilhados, ['categoria' => $categoria['slug']])) ?>"
                      class="filtro-link <?= $categoriaSlug === $categoria['slug'] ? 'active' : '' ?>">
                      <?= htmlspecialchars($categoria['nome']) ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <div class="col-lg-9">
        <div class="produtos-grid mx-auto">
          <!-- If empty -->
          <?php if (empty($produtos)) : ?>
            <div class="produtos-vazio">
              <p>
                Nenhum produto encontrado<?= $buscaAtiva ? ' para "' . htmlspecialchars($busca) . '"' : '' ?>.
              </p>
              <?php if ($filtrosAtivos) : ?>
                <a href="<?= $baseUrl ?>/produtos" class="btn btn-outline-primary py-2 px-4 rounded-5 fw-medium">Ver Todos os Produtos</a>
              <?php endif; ?>
            </div>
          <?php endif; ?>
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
                  <?= formatarPreco($produto['preco']) ?>
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>