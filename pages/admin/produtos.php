<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

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

<?php require_once __DIR__ . '/../../includes/admin_head.php'; ?>

<main class="container py-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
      <a class="link-secondary text-decoration-none" href="<?= e($baseUrl) ?>/admin/dashboard">
        <i class="bi bi-arrow-left"></i>
        Painel
      </a>
      <h1 class="h3 mt-2 mb-0">Produtos</h1>
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
          <h2 class="h5 mb-3"><?= $produtoEditando ? 'Editar produto' : 'Novo produto' ?></h2>
          <form method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3">
            <input type="hidden" name="id" value="<?= e($produtoEditando['id'] ?? '') ?>">
            <div>
              <label class="form-label" for="nome">Nome</label>
              <input class="form-control" type="text" id="nome" name="nome" required value="<?= e($produtoEditando['nome'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label" for="descricao">Descricao</label>
              <textarea class="form-control" id="descricao" name="descricao" rows="4"><?= e($produtoEditando['descricao'] ?? '') ?></textarea>
            </div>
            <div>
              <label class="form-label" for="preco">Preco</label>
              <input class="form-control" type="number" id="preco" name="preco" min="0" step="0.01" required value="<?= e($produtoEditando['preco'] ?? '') ?>">
            </div>
            <div>
              <label class="form-label" for="categoria_id">Categoria</label>
              <select class="form-select" id="categoria_id" name="categoria_id" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?= e($categoria['id']) ?>" <?= (string) ($produtoEditando['categoria_id'] ?? '') === (string) $categoria['id'] ? 'selected' : '' ?>>
                    <?= e($categoria['nome']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label class="form-label" for="imagem">Imagem</label>
              <input class="form-control" type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp">
              <?php if (!empty($produtoEditando['imagem'])): ?>
                <div class="small text-muted mt-1">Atual: <?= e($produtoEditando['imagem']) ?></div>
              <?php endif; ?>
            </div>
            <div class="d-flex gap-2">
              <button class="btn btn-primary" type="submit">
                <i class="bi bi-check-lg"></i>
                Salvar
              </button>
              <?php if ($produtoEditando): ?>
                <a class="btn btn-outline-secondary" href="<?= e($baseUrl) ?>/admin/produtos">Cancelar</a>
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
                <th>Thumbnail</th>
                <th>Nome</th>
                <th>Preco</th>
                <th>Categoria</th>
                <th class="text-end">Acoes</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($produtos as $produto): ?>
                <tr>
                  <td>
                    <img src="<?= e($baseUrl) ?>/uploads/<?= e($produto['imagem']) ?>" alt="<?= e($produto['nome']) ?>" width="56" height="56" class="rounded object-fit-cover" onerror="this.onerror=null;this.src='<?= e($baseUrl) ?>/assets/images/fallback.jpg';">
                  </td>
                  <td><?= e($produto['nome']) ?></td>
                  <td><?= e(formatarPreco($produto['preco'])) ?></td>
                  <td><?= e($produto['categoria_nome']) ?></td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-outline-secondary" href="<?= e($baseUrl) ?>/admin/produtos?id=<?= e($produto['id']) ?>">
                      <i class="bi bi-pencil"></i>
                      Editar
                    </a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Excluir este produto?');">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= e($produto['id']) ?>">
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