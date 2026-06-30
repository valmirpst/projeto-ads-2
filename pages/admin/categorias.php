<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

$baseUrl = $baseUrl ?? '';
$mensagemErro = '';
$categoriaEditando = null;
$idEditando = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : null;

if ($idEditando) {
  $stmt = $pdo->prepare('SELECT id, nome, slug, ordem, imagem FROM categoria WHERE id = :id');
  $stmt->execute([':id' => $idEditando]);
  $categoriaEditando = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'] ?? 'salvar';

  try {
    if ($acao === 'excluir') {
      $id = (int) ($_POST['id'] ?? 0);
      $stmt = $pdo->prepare('SELECT imagem FROM categoria WHERE id = :id');
      $stmt->execute([':id' => $id]);
      $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

      $stmt = $pdo->prepare('DELETE FROM categoria WHERE id = :id');
      $stmt->execute([':id' => $id]);

      if ($categoria) {
        removerImagemUpload($categoria['imagem']);
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
      $stmt = $pdo->prepare('SELECT imagem FROM categoria WHERE id = :id');
      $stmt->execute([':id' => $id]);
      $categoriaAtual = $stmt->fetch(PDO::FETCH_ASSOC);
      $imagemAtual = $categoriaAtual['imagem'] ?? null;
    }

    $imagem = salvarImagemUpload('imagem', $imagemAtual, true);

    if ($id) {
      $stmt = $pdo->prepare('UPDATE categoria SET nome = :nome, slug = :slug, ordem = :ordem, imagem = :imagem WHERE id = :id');
      $stmt->execute([
        ':nome' => $nome,
        ':slug' => $slug,
        ':ordem' => $ordem,
        ':imagem' => $imagem,
        ':id' => $id,
      ]);
      definirMensagem('success', 'Categoria atualizada com sucesso.');
    } else {
      $stmt = $pdo->prepare('INSERT INTO categoria (nome, slug, ordem, imagem) VALUES (:nome, :slug, :ordem, :imagem)');
      $stmt->execute([
        ':nome' => $nome,
        ':slug' => $slug,
        ':ordem' => $ordem,
        ':imagem' => $imagem,
      ]);
      definirMensagem('success', 'Categoria criada com sucesso.');
    }

    header('Location: ' . $baseUrl . '/admin/categorias');
    exit;
  } catch (Throwable $e) {
    $mensagemErro = $e->getMessage();
  }
}

$stmt = $pdo->prepare('SELECT id, nome, slug, ordem, imagem FROM categoria ORDER BY ordem, nome');
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
$mensagem = obterMensagem();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Categorias - Admin ManuMake</title>
  <link rel="icon" type="image/x-icon" href="<?= e($baseUrl) ?>/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
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
                      <a class="btn btn-sm btn-outline-primary" href="<?= e($baseUrl) ?>/admin/categorias?id=<?= e($categoria['id']) ?>">
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
</body>

</html>
