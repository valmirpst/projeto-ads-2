<?php
// Esta página é carregada pelo index.php (front controller).
defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once __DIR__ . '/../../includes/admin_auth.php';

$baseUrl = $baseUrl ?? '';
$mensagemErro = '';
$produtoEditando = null;
$idEditando = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : null;

if ($idEditando) {
  $produtoEditando = buscarProdutoAdminPorId($pdo, $idEditando);
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

    if ($id) {
      definirMensagem('success', 'Produto atualizado com sucesso.');
    } else {
      definirMensagem('success', 'Produto criado com sucesso.');
    }

    header('Location: ' . $baseUrl . '/admin/produtos');
    exit;
  } catch (Throwable $e) {
    $mensagemErro = $e->getMessage();
  }
}

$categorias = buscarCategorias($pdo);
$produtos = listarProdutosAdmin($pdo);
$mensagem = obterMensagem();
$adminTitulo = 'Produtos - Admin ManuMake';
?>

<?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

<main class="container py-4">
  <div class="mb-3 border-bottom pb-4">
    <a class="text-decoration-none text-muted" href="<?= e($baseUrl) ?>/admin/dashboard">
      <i class="bi bi-arrow-left me-1"></i>
      Voltar ao Painel
    </a>
    <h1 class="h3 mt-2 mb-0">Produtos</h1>
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
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
              <thead class="table-light">
                <tr>
                  <th class="small px-3 py-3 border-0">Produto</th>
                  <th class="small py-3 border-0">Preço</th>
                  <th class="small py-3 border-0">Categoria</th>
                  <th class="small px-3 py-3 border-0 text-end">Ações</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($produtos as $produto): ?>
                  <tr>
                    <td class="fw-medium text-dark">
                      <div class="d-flex align-items-center gap-2">
                        <?php if (!empty($produto['imagem'])): ?>
                          <div style="width:56px;height:56px;flex:0 0 56px;">
                            <img
                              src=" <?= e($baseUrl) ?>/uploads/<?= e($produto['imagem']) ?>"
                              alt="<?= e($produto['nome']) ?>"
                              class="w-100 h-100 rounded object-fit-cover shadow-sm border"
                              loading="lazy"
                              onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                          </div>
                        <?php endif; ?>
                        <span><?= e($produto['nome']) ?></span>
                      </div>
                    </td>
                    <td><?= e(formatarPreco($produto['preco'])) ?></td>
                    <td>
                      <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill">
                        <?= e($produto['categoria_nome']) ?>
                      </span>
                    </td>
                    <td class="text-end">
                      <div class="d-flex gap-2 justify-content-end">
                        <a class="btn btn-sm btn-light text-secondary border" href="<?= e($baseUrl) ?>/admin/produtos?id=<?= e($produto['id']) ?>" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </a>
                        <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                          <input type="hidden" name="acao" value="excluir">
                          <input type="hidden" name="id" value="<?= e($produto['id']) ?>">
                          <button class="btn btn-sm btn-light text-danger border" type="submit" title="Excluir">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php if (empty($produtos)): ?>
                  <tr>
                    <td colspan="4" class="text-center py-5 text-muted">Nenhum produto cadastrado.</td>
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