<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

$baseUrl = $baseUrl ?? '';
$erro = '';

if (!empty($_SESSION['admin_usuario_id'])) {
  header('Location: ' . $baseUrl . '/admin/dashboard');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $usuario = trim($_POST['usuario'] ?? '');
  $senha = $_POST['senha'] ?? '';

  $stmt = $pdo->prepare('SELECT id, usuario, senha FROM usuario WHERE usuario = :usuario LIMIT 1');
  $stmt->execute([':usuario' => $usuario]);
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

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

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Administrativo - ManuMake</title>
  <link rel="icon" type="image/x-icon" href="<?= e($baseUrl) ?>/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body class="bg-light">
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
</body>

</html>
