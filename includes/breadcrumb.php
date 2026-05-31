<?php
$baseUrl = $baseUrl ?? '';
$breadcrumbs = $breadcrumbs ?? [];

if (count($breadcrumbs) < 2) {
  return;
}
?>

<nav aria-label="breadcrumb" class="container container-sm pt-4 px-4">
  <ol class="breadcrumb mb-0 small">
    <?php foreach ($breadcrumbs as $index => $breadcrumb) : ?>
      <?php $isLast = $index === array_key_last($breadcrumbs); ?>
      <li class="text-black breadcrumb-item <?= $isLast ? 'active' : '' ?>" <?= $isLast ? 'aria-current="page"' : '' ?>>
        <?php if ($isLast) : ?>
          <?= $breadcrumb['name'] ?>
        <?php else : ?>
          <a class="text-decoration-none text-muted" href="<?= $breadcrumb['href'] ?>"><?= $breadcrumb['name'] ?></a>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>