<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

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
    $slug = trim($_POST['slug'] ?? '');
    $ordem = (int) ($_POST['ordem'] ?? 0);

    if ($nome === '') {
      throw new RuntimeException('Informe o nome da categoria.');
    }

    $slug = $slug !== '' ? gerarSlug($slug) : gerarSlug($nome);
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
?>

<?php require_once __DIR__ . '/../../includes/admin_head.php'; ?>

<main class="container py-4">
  <div class="mb-3 border-bottom pb-4">
    <a class="text-decoration-none text-muted" href="<?= e($baseUrl) ?>/admin/dashboard">
      <i class="bi bi-arrow-left me-1"></i>
      Voltar ao Painel
    </a>
    <h1 class="h3 mt-2 mb-0">Categorias</h1>
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
              <label class="form-label text-secondary small fw-bold" for="slug">Slug</label>
              <input class="form-control" type="text" id="slug" name="slug" value="<?= e($categoriaEditando['slug'] ?? '') ?>">
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
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
              <thead class="table-light">
                <tr>
                  <th class="small px-3 py-3 border-0">Categoria</th>
                  <th class="small py-3 border-0">Slug</th>
                  <th class="small py-3 border-0 text-center">Ordem</th>
                  <th class="small px-3 py-3 border-0 text-end">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($categorias as $categoria): ?>
                  <tr>
                    <td class="fw-medium text-dark">
                      <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($categoria['imagem'])): ?>
                          <div style="width:56px;height:56px;flex:0 0 56px;">
                            <img
                              src="<?= e($baseUrl) ?>/uploads/<?= e($categoria['imagem']) ?>"
                              alt="<?= e($categoria['nome']) ?>"
                              class="w-100 h-100 rounded object-fit-cover shadow-sm border"
                              onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                          </div>
                        <?php endif; ?>
                        <span><?= e($categoria['nome']) ?></span>
                      </div>
                    </td>
                    <td class="text-muted"><?= e($categoria['slug']) ?></td>
                    <td class="text-center">
                      <span class="badge bg-light text-dark border"><?= e($categoria['ordem']) ?></span>
                    </td>
                    <td class="text-end">
                      <div class="d-flex gap-2 justify-content-end">
                        <a class="btn btn-sm btn-light text-secondary border" href="<?= e($baseUrl) ?>/admin/categorias?id=<?= e($categoria['id']) ?>" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                          <input type="hidden" name="acao" value="excluir">
                          <input type="hidden" name="id" value="<?= e($categoria['id']) ?>">
                          <button class="btn btn-sm btn-light text-danger border" type="submit" title="Excluir">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($categorias)): ?>
                  <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Nenhuma categoria cadastrada.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>