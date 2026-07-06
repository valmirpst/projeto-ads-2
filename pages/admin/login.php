<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

$baseUrl = $baseUrl ?? '';
$erro = '';
$adminTitulo = 'Login Administrativo - ManuMake';

if (!empty($_SESSION['admin_usuario_id'])) {
  header('Location: ' . $baseUrl . '/admin/dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = trim($_POST['usuario'] ?? '');
  $senha = $_POST['senha'] ?? '';

  $admin = buscarUsuarioPorLogin($pdo, $usuario);

  if ($admin && password_verify($senha, $admin['senha'])) {
    session_regenerate_id(true);
    $_SESSION['admin_usuario_id'] = (int) $admin['id'];
    $_SESSION['admin_usuario'] = $admin['usuario'];
    header('Location: ' . $baseUrl . '/admin/dashboard');
    exit;
  }

  $erro = 'Usuario ou senha invalidos.';
}
?>

<?php require_once __DIR__ . '/../../includes/admin_head.php'; ?>

<main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
  <div class="card shadow-sm" style="max-width: 420px; width: 100%;">
    <div class="card-body p-4">
      <h1 class="h4 mb-4">Login Administrativo</h1>

      <?php if ($erro): ?>
        <div class="alert alert-danger" role="alert"><?= e($erro) ?></div>
      <?php endif; ?>

      <form method="post" class="d-flex flex-column gap-3">
        <div>
          <label class="form-label" for="usuario">Usuario</label>
          <input class="form-control" type="text" id="usuario" name="usuario" required autofocus value="<?= e($_POST['usuario'] ?? '') ?>">
        </div>
        <div>
          <label class="form-label" for="senha">Senha</label>
          <input class="form-control" type="password" id="senha" name="senha" required>
        </div>
        <button class="btn btn-primary" type="submit">
          <i class="bi bi-box-arrow-in-right"></i>
          Entrar
        </button>
      </form>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../../includes/admin_footer.php'; ?>