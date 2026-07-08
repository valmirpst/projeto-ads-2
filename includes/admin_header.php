<?php
$baseUrl = $baseUrl ?? '';
$adminTitulo = $adminTitulo ?? 'Admin ManuMake';
$adminBodyClass = $adminBodyClass ?? 'bg-light';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($adminTitulo) ?></title>
  <link rel="icon" type="image/x-icon" href="<?= e($baseUrl) ?>/favicon.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/custom.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/header.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/footer.css">
  <!-- CSS dinâmico de cada página -->
  <?php
  if (!empty($stylesheet)) {
    $pageCssPath = "assets/css/pages/$stylesheet";

    if (file_exists($pageCssPath)) {
      echo "<link rel='stylesheet' href='" . $baseUrl . "/$pageCssPath'>";
    }
  }
  ?>
</head>

<body class="<?= e($adminBodyClass) ?>">