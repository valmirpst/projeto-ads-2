<?php
// Esta página é carregada pelo index.php (front controller).
defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once __DIR__ . '/../../includes/admin_auth.php';

$baseUrl = $baseUrl ?? '';
$mensagemErro = '';
$categoriaEditando = null;
$idEditando = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : null;

if ($idEditando) {
  $categoriaEditando = buscarCategoriaAdminPorId($pdo, $idEditando);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'] ?? 'salvar';

  try {
    if ($acao === 'excluir') {
      $id = (int) ($_POST['id'] ?? 0);
      $imagem = buscarImagemCategoria($pdo, $id);
      excluirCategoria($pdo, $id);

      if ($imagem) {
        removerImagemUpload($imagem);
      }

      definirMensagem('success', 'Categoria excluida com sucesso.');
      header('Location: ' . $baseUrl . '/admin/categorias');
      exit;
    }

    $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
    $nome = trim($_POST['nome'] ?? '');
    $ordem = (int) ($_POST['ordem'] ?? 0);

    if ($nome === '') {
      throw new RuntimeException('Informe o nome da categoria.');
    }

    $slug = gerarSlug($nome);
    $imagemAtual = null;

    if ($id) {
      $imagemAtual = buscarImagemCategoria($pdo, $id);
    }

    $imagem = salvarImagemUpload('imagem', $imagemAtual, true);
    salvarCategoria($pdo, $id, $nome, $slug, $ordem, $imagem);

    if ($id) {
      definirMensagem('success', 'Categoria atualizada com sucesso.');
    } else {
      definirMensagem('success', 'Categoria criada com sucesso.');
    }

    header('Location: ' . $baseUrl . '/admin/categorias');
    exit;
  } catch (Throwable $e) {
    $mensagemErro = $e->getMessage();
  }
}

$categorias = listarCategoriasAdmin($pdo);
$mensagem = obterMensagem();
$adminTitulo = 'Categorias - Admin ManuMake';
$adminPagina = $adminPagina ?? 'categorias';
?>

<?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

<main class="container py-4">
  <div class="mb-3 border-bottom pb-4">
    <h1 class="h3 mb-0">Categorias</h1>
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
          <h2 class="h5 fw-semibold mb-3"><?= $categoriaEditando ? 'Editar categoria' : 'Nova categoria' ?></h2>
          <form method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            <input type="hidden" name="id" value="<?= e($categoriaEditando['id'] ?? '') ?>">
            <div>
              <label class="form-label text-secondary small fw-bold" for="nome">Nome</label>
              <input class="form-control" type="text" id="nome" name="nome" required value="<?= e($categoriaEditando['nome'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="ordem">Ordem</label>
              <input class="form-control" type="number" id="ordem" name="ordem" min="0" value="<?= e($categoriaEditando['ordem'] ?? 0) ?>">
            </div>
            <div>
              <label class="form-label text-secondary small fw-bold" for="imagem">Imagem</label>
              <input class="form-control" type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp">
              <?php if (!empty($categoriaEditando['imagem'])): ?>
                <div class="small text-muted mt-2 d-flex align-items-center gap-2">
                  <i class="bi bi-image"></i>
                  <span>Atual: <?= e($categoriaEditando['imagem']) ?></span>
                </div>
              <?php endif; ?>
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
              <button class="btn btn-primary w-100 w-sm-auto px-4" type="submit">
                <i class="bi bi-check-lg me-1"></i>
                Salvar
              </button>
              <?php if ($categoriaEditando): ?>
                <a class="btn btn-outline-secondary w-100 w-sm-auto" href="<?= e($baseUrl) ?>/admin/categorias">Cancelar</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="col-12 col-lg-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 fw-semibold mb-0">Categorias Cadastradas</h2>
        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
          <?= count($categorias) ?> <?= count($categorias) === 1 ? 'categoria' : 'categorias' ?>
        </span>
      </div>

      <?php if (empty($categorias)): ?>
        <div class="card border-0 shadow-sm p-5 text-center text-muted">
          <i class="bi bi-folder-x display-4 d-block mb-3 text-secondary opacity-50"></i>
          <p class="mb-0">Nenhuma categoria cadastrada.</p>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($categorias as $categoria): ?>
            <?php $isEditando = $categoriaEditando && (int) $categoriaEditando['id'] === (int) $categoria['id']; ?>
            <div class="card border-0 shadow-sm <?= $isEditando ? 'border-start border-4 border-primary' : '' ?>">
              <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                  <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($categoria['imagem'])): ?>
                      <div class="flex-shrink-0" style="width:56px;height:56px;">
                        <img
                          src="<?= e($baseUrl) ?>/uploads/<?= e($categoria['imagem']) ?>"
                          alt="<?= e($categoria['nome']) ?>"
                          class="w-100 h-100 rounded object-fit-cover shadow-sm border"
                          loading="lazy"
                          onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                      </div>
                    <?php else: ?>
                      <div class="flex-shrink-0 bg-light rounded d-flex align-items-center justify-content-center border text-muted" style="width:56px;height:56px;">
                        <i class="bi bi-folder fs-4"></i>
                      </div>
                    <?php endif; ?>

                    <div>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="h6 fw-bold text-dark mb-0"><?= e($categoria['nome']) ?></h3>
                        <?php if ($isEditando): ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                            Editando
                          </span>
                        <?php endif; ?>
                      </div>
                      <div class="mt-1">
                        <span class="badge bg-light text-secondary border fw-normal">
                          <i class="bi bi-sort-numeric-down me-1"></i>Ordem: <?= e($categoria['ordem']) ?>
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0">
                    <a class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-3 py-2"
                      href="<?= e($baseUrl) ?>/admin/categorias?id=<?= e($categoria['id']) ?>"
                      title="Editar Categoria">
                      <i class="bi bi-pencil"></i>
                      <span>Editar</span>
                    </a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= e($categoria['id']) ?>">
                      <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-2"
                        type="submit"
                        title="Excluir Categoria">
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