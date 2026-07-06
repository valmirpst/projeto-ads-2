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
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <a class="link-secondary text-decoration-none" href="<?= e($baseUrl) ?>/admin/dashboard">
        <i class="bi bi-arrow-left"></i>
        Painel
      </a>
      <h1 class="h3 mt-2 mb-0">Categorias</h1>
    </div>
    <a class="btn btn-outline-danger" href="<?= e($baseUrl) ?>/admin/logout">
      <i class="bi bi-box-arrow-right"></i>
      Sair
    </a>
  </div>

  <?php if ($mensagem): ?>
    <div class="alert alert-<?= e($mensagem['tipo']) ?>" role="alert"><?= e($mensagem['texto']) ?></div>
  <?php endif; ?>
  <?php if ($mensagemErro): ?>
    <div class="alert alert-danger" role="alert"><?= e($mensagemErro) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <section class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h2 class="h5 mb-3"><?= $categoriaEditando ? 'Editar categoria' : 'Nova categoria' ?></h2>
          <form method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            <input type="hidden" name="id" value="<?= e($categoriaEditando['id'] ?? '') ?>">
            <div>
              <label class="form-label" for="nome">Nome</label>
              <input class="form-control" type="text" id="nome" name="nome" required value="<?= e($categoriaEditando['nome'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label" for="slug">Slug</label>
              <input class="form-control" type="text" id="slug" name="slug" value="<?= e($categoriaEditando['slug'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label" for="ordem">Ordem</label>
              <input class="form-control" type="number" id="ordem" name="ordem" min="0" value="<?= e($categoriaEditando['ordem'] ?? 0) ?>">
            </div>
            <div>
              <label class="form-label" for="imagem">Imagem</label>
              <input class="form-control" type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp">
              <?php if (!empty($categoriaEditando['imagem'])): ?>
                <div class="small text-muted mt-1">Atual: <?= e($categoriaEditando['imagem']) ?></div>
              <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary" type="submit">
                <i class="bi bi-check-lg"></i>
                Salvar
              </button>
              <?php if ($categoriaEditando): ?>
                <a class="btn btn-outline-secondary" href="<?= e($baseUrl) ?>/admin/categorias">Cancelar</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="col-lg-8">
      <div class="card">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Slug</th>
                <th>Ordem</th>
                <th class="text-end">Acoes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categorias as $categoria): ?>
                <tr>
                  <td><?= e($categoria['nome']) ?></td>
                  <td><?= e($categoria['slug']) ?></td>
                  <td><?= e($categoria['ordem']) ?></td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="<?= e($baseUrl) ?>/admin/categorias?id=<?= e($categoria['id']) ?>">
                      <i class="bi bi-pencil"></i>
                      Editar
                    </a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Excluir esta categoria?');">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= e($categoria['id']) ?>">
                      <button class="btn btn-sm btn-outline-danger" type="submit">
                        <i class="bi bi-trash"></i>
                        Excluir
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>