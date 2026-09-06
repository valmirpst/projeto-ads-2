<?php
// Esta página é carregada pelo index.php (front controller).
defined('APP_ROOT') || die('Acesso direto não permitido.');

require_once __DIR__ . '/../../includes/admin_auth.php';

$baseUrl = $baseUrl ?? '';
$mensagemErro = '';
$caracteristicaEditando = null;
$idEditando = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : null;

if ($idEditando) {
  $caracteristicaEditando = buscarCaracteristicaAdminPorId($pdo, $idEditando);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $acao = $_POST['acao'] ?? 'salvar';

  try {
    if ($acao === 'excluir') {
      $id = (int) ($_POST['id'] ?? 0);
      excluirCaracteristica($pdo, $id);

      definirMensagem('success', 'Característica excluída com sucesso.');
      header('Location: ' . $baseUrl . '/admin/caracteristicas');
      exit;
    }

    $id = !empty($_POST['id']) ? (int) $_POST['id'] : null;
    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
      throw new RuntimeException('Informe o nome da característica.');
    }

    salvarCaracteristica($pdo, $id, $nome);

    if ($id) {
      definirMensagem('success', 'Característica atualizada com sucesso.');
    } else {
      definirMensagem('success', 'Característica criada com sucesso.');
    }

    header('Location: ' . $baseUrl . '/admin/caracteristicas');
    exit;
  } catch (Throwable $e) {
    $mensagemErro = $e->getMessage();
  }
}

$caracteristicas = buscarCaracteristicas($pdo);
$mensagem = obterMensagem();
$adminTitulo = 'Características - Admin ManuMake';
$adminPagina = $adminPagina ?? 'caracteristicas';
?>

<?php require_once __DIR__ . '/../../includes/admin_header.php'; ?>

<main class="container py-4">
  <div class="mb-3 border-bottom pb-4">
    <h1 class="h3 mb-0">Características</h1>
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
          <h2 class="h5 fw-semibold mb-3"><?= $caracteristicaEditando ? 'Editar característica' : 'Nova característica' ?></h2>
          <form method="post" class="d-flex flex-column gap-3">
            <input type="hidden" name="acao" value="salvar">
            <input type="hidden" name="id" value="<?= e($caracteristicaEditando['id'] ?? '') ?>">
            <div>
              <label class="form-label text-secondary small fw-bold" for="nome">Nome</label>
              <input class="form-control" type="text" id="nome" name="nome" required value="<?= e($caracteristicaEditando['nome'] ?? '') ?>" placeholder="Ex: Vegano, Hidratante...">
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
              <button class="btn btn-primary w-100 w-sm-auto px-4" type="submit">
                <i class="bi bi-check-lg me-1"></i>
                Salvar
              </button>
              <?php if ($caracteristicaEditando): ?>
                <a class="btn btn-outline-secondary w-100 w-sm-auto" href="<?= e($baseUrl) ?>/admin/caracteristicas">Cancelar</a>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </section>

    <section class="col-12 col-lg-8">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="h5 fw-semibold mb-0">Características Cadastradas</h2>
        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill px-3 py-2">
          <?= count($caracteristicas) ?> <?= count($caracteristicas) === 1 ? 'característica' : 'características' ?>
        </span>
      </div>

      <?php if (empty($caracteristicas)): ?>
        <div class="card border-0 shadow-sm p-5 text-center text-muted">
          <i class="bi bi-bookmark-x display-4 d-block mb-3 text-secondary opacity-50"></i>
          <p class="mb-0">Nenhuma característica cadastrada.</p>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($caracteristicas as $caracteristica): ?>
            <?php $isEditando = $caracteristicaEditando && (int) $caracteristicaEditando['id'] === (int) $caracteristica['id']; ?>
            <div class="card border-0 shadow-sm <?= $isEditando ? 'border-start border-4 border-primary' : '' ?>">
              <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                  <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded d-flex align-items-center justify-content-center border border-warning-subtle" style="width:48px;height:48px;">
                      <i class="bi bi-bookmark-star fs-5"></i>
                    </div>

                    <div>
                      <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h3 class="h6 fw-bold text-dark mb-0"><?= e($caracteristica['nome']) ?></h3>
                        <?php if ($isEditando): ?>
                          <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                            Editando
                          </span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0">
                    <a class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 px-3 py-2"
                      href="<?= e($baseUrl) ?>/admin/caracteristicas?id=<?= e($caracteristica['id']) ?>"
                      title="Editar Característica">
                      <i class="bi bi-pencil"></i>
                      <span>Editar</span>
                    </a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta característica?');">
                      <input type="hidden" name="acao" value="excluir">
                      <input type="hidden" name="id" value="<?= e($caracteristica['id']) ?>">
                      <button class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-3 py-2"
                        type="submit"
                        title="Excluir Característica">
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