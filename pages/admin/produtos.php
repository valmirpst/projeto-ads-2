<?php
// Esta página é carregada pelo index.php (front controller).
defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once __DIR__ . '/../../includes/admin_auth.php';

$baseUrl = $baseUrl ?? '';
$mensagemErro = '';
$produtoEditando = null;
$idEditando = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : null;

$caracteristicas = buscarCaracteristicas($pdo);
$caracteristicasVinculadas = [];

if ($idEditando) {
  $produtoEditando = buscarProdutoAdminPorId($pdo, $idEditando);
  if ($produtoEditando) {
    $caracteristicasVinculadas = buscarCaracteristicasDoProdutoIds($pdo, $idEditando);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'] ?? 'salvar';

  try {
    if ($acao === 'excluir') {
      $id = (int) ($_POST['id'] ?? 0);
      $imagem = buscarImagemProduto($pdo, $id);
      excluirProduto($pdo, $id);

      if ($imagem) {
        removerImagemUpload($imagem);
      }

      definirMensagem('success', 'Produto excluido com sucesso.');
      header('Location: ' . $baseUrl . '/admin/produtos');
      exit;
    }

    $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = str_replace(',', '.', trim($_POST['preco'] ?? '0'));
    $categoriaId = (int) ($_POST['categoria_id'] ?? 0);

    if ($nome === '' || $categoriaId <= 0 || !is_numeric($preco)) {
      throw new RuntimeException('Preencha nome, preco e categoria corretamente.');
    }

    $imagemAtual = null;

    if ($id) {
      $imagemAtual = buscarImagemProduto($pdo, $id);
    }

    $imagem = salvarImagemUpload('imagem', $imagemAtual, true);
    salvarProduto($pdo, $id, $nome, $descricao, $preco, $categoriaId, $imagem);

    $produtoId = $id ?: (int) $pdo->lastInsertId();
    $caracteristicasPost = $_POST['caracteristicas'] ?? [];
    $caracteristicasIds = is_array($caracteristicasPost)
      ? array_values(array_filter(array_map('intval', $caracteristicasPost), fn($cid) => $cid > 0))
      : [];

    sincronizarCaracteristicasProduto($pdo, $produtoId, $caracteristicasIds);

    if ($id) {
      definirMensagem('success', 'Produto atualizado com sucesso.');
    } else {
      definirMensagem('success', 'Produto criado com sucesso.');
    }

    header('Location: ' . $baseUrl . '/admin/produtos');
    exit;
  } catch (Throwable $e) {
    $mensagemErro = $e->getMessage();
    if (isset($_POST['caracteristicas']) && is_array($_POST['caracteristicas'])) {
      $caracteristicasVinculadas = array_values(array_filter(array_map('intval', $_POST['caracteristicas']), fn($cid) => $cid > 0));
    }
  }
}

$categorias = buscarCategorias($pdo);
$produtos = listarProdutosAdmin($pdo);
$mensagem = obterMensagem();
$adminTitulo = 'Produtos - Admin ManuMake';
$adminPagina = $adminPagina ?? 'produtos';
?>

<?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

<main class="container py-4">
  <div class="mb-3 border-bottom pb-4">
    <h1 class="h3 mb-0">Produtos</h1>
  </div>

  <?php if ($mensagem): ?>
    <div class="alert alert-<?= e($mensagem['tipo']) ?>" role="alert"><?= e($mensagem['texto']) ?></div>
  <?php endif; ?>
  <?php if ($mensagemErro): ?>
    <div class="alert alert-danger" role="alert"><?= e($mensagemErro) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <section class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <h2 class="h5 fw-semibold mb-3"><?= $produtoEditando ? 'Editar produto' : 'Novo produto' ?></h2>
          <form method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            <input type="hidden" name="id" value="<?= e($produtoEditando['id'] ?? '') ?>">
            <div>
              <label class="form-label text-secondary small fw-bold" for="nome">Nome</label>
              <input class="form-control" type="text" id="nome" name="nome" required value="<?= e($produtoEditando['nome'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="descricao">Descrição</label>
              <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= e($produtoEditando['descricao'] ?? '') ?></textarea>
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="preco">Preço (R$)</label>
              <input class="form-control" type="number" id="preco" name="preco" min="0" step="0.01" required value="<?= e($produtoEditando['preco'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="categoria_id">Categoria</label>
              <select class="form-select" id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?= e($categoria['id']) ?>" <?= (string) ($produtoEditando['categoria_id'] ?? '') === (string) $categoria['id'] ? 'selected' : '' ?>>
                    <?= e($categoria['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="imagem">Imagem</label>
              <input class="form-control" type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp">
              <?php if (!empty($produtoEditando['imagem'])): ?>
                <div class="small text-muted mt-2 d-flex align-items-center gap-2">
                  <i class="bi bi-image"></i>
                  <span>Atual: <?= e($produtoEditando['imagem']) ?></span>
                </div>
              <?php endif; ?>
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold mb-2">Características</label>
              <?php if (empty($caracteristicas)): ?>
                <p class="text-muted small mb-0">Nenhuma característica cadastrada.</p>
              <?php else: ?>
                <div class="border rounded p-3 bg-light-subtle">
                  <div class="row g-2">
                    <?php foreach ($caracteristicas as $caracteristica): ?>
                      <div class="col-6">
                        <div class="form-check">
                          <input
                            class="form-check-input"
                            type="checkbox"
                            name="caracteristicas[]"
                            value="<?= e($caracteristica['id']) ?>"
                            id="caracteristica_<?= e($caracteristica['id']) ?>"
                            <?= in_array((int) $caracteristica['id'], $caracteristicasVinculadas, true) ? 'checked' : '' ?>>
                          <label class="form-check-label small user-select-none" for="caracteristica_<?= e($caracteristica['id']) ?>" style="cursor: pointer;">
                            <?= e($caracteristica['nome']) ?>
                          </label>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
              <button class="btn btn-primary w-100 w-sm-auto px-4" type="submit">
                <i class="bi bi-check-lg me-1"></i>
                Salvar
              </button>
              <?php if ($produtoEditando): ?>
                <a class="btn btn-outline-secondary w-100 w-sm-auto" href="<?= e($baseUrl) ?>/admin/produtos">Cancelar</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="col-12 col-lg-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 fw-semibold mb-0">Produtos Cadastrados</h2>
        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
          <?= count($produtos) ?> <?= count($produtos) === 1 ? 'produto' : 'produtos' ?>
        </span>
      </div>

      <?php if (empty($produtos)): ?>
        <div class="card border-0 shadow-sm p-5 text-center text-muted">
          <i class="bi bi-box-seam display-4 d-block mb-3 text-secondary opacity-50"></i>
          <p class="mb-0">Nenhum produto cadastrado.</p>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($produtos as $produto): ?>
            <?php $isEditando = $produtoEditando && (int) $produtoEditando['id'] === (int) $produto['id']; ?>
            <div class="card border-0 shadow-sm <?= $isEditando ? 'border-start border-4 border-primary' : '' ?>">
              <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                  <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($produto['imagem'])): ?>
                      <div class="flex-shrink-0" style="width:56px;height:56px;">
                        <img
                          src="<?= e($baseUrl) ?>/uploads/<?= e($produto['imagem']) ?>"
                          alt="<?= e($produto['nome']) ?>"
                          class="w-100 h-100 rounded object-fit-cover shadow-sm border"
                          loading="lazy"
                          onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                      </div>
                    <?php else: ?>
                      <div class="flex-shrink-0 bg-light rounded d-flex align-items-center justify-content-center border text-muted" style="width:56px;height:56px;">
                        <i class="bi bi-box-seam fs-4"></i>
                      </div>
                    <?php endif; ?>

                    <div>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="h6 fw-bold text-dark mb-0"><?= e($produto['nome']) ?></h3>
                        <?php if ($isEditando): ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                            Editando
                          </span>
                        <?php endif; ?>
                      </div>
                      <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                        <span class="fw-semibold text-primary">
                          <?= e(formatarPreco($produto['preco'])) ?>
                        </span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill fw-normal">
                          <?= e($produto['categoria_nome']) ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0">
                    <a class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-3 py-2"
                      href="<?= e($baseUrl) ?>/admin/produtos?id=<?= e($produto['id']) ?>"
                      title="Editar Produto">
                      <i class="bi bi-pencil"></i>
                      <span>Editar</span>
                    </a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= e($produto['id']) ?>">
                      <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-2"
                        type="submit"
                        title="Excluir Produto">
                        <i class="bi bi-trash"></i>
                        <span>Excluir</span>
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>