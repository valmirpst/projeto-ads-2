<?php
$baseUrl = $baseUrl ?? '';
$categorias = [
  ['id' => 1, 'nome' => 'Body Splash', 'slug' => 'body-splash', 'imagem' => 'body-splash.jpg'],
  ['id' => 2, 'nome' => 'Bolsas e Mochilas', 'slug' => 'bolsas-mochilas', 'imagem' => 'bolsas-mochilas.jpg'],
  ['id' => 3, 'nome' => 'Copos e Garrafas Térmicas', 'slug' => 'copos-garrafas-termicas', 'imagem' => 'copos-garrafas-termicas.jpg'],
  ['id' => 4, 'nome' => 'Cuidado para o Cabelo', 'slug' => 'cuidado-cabelo', 'imagem' => 'cuidado-cabelo.jpg'],
  ['id' => 5, 'nome' => 'Cuidado com a Pele', 'slug' => 'cuidado-pele', 'imagem' => 'cuidado-pele.jpg'],
  ['id' => 6, 'nome' => 'Gloss Labial', 'slug' => 'gloss-labial', 'imagem' => 'gloss-labial.jpg'],
  ['id' => 7, 'nome' => 'Kits para Presentes', 'slug' => 'kits-presentes', 'imagem' => 'kits-presentes.jpg'],
  ['id' => 8, 'nome' => 'Maquiagem', 'slug' => 'maquiagem', 'imagem' => 'maquiagem.jpg'],
]
?>


<main class="container flex-grow-1 d-flex flex-column gap-5">
  <!-- Hero -->
  <section class="hero-section container-sm text-center pt-112">
    <div class="hero-content mx-auto d-flex flex-column gap-3">
      <h1 class="display-1 ">BELEZA QUE IMPRESSIONA</h1>
      <p class="hero-subtitle fw-light mb-4">Realce sua essência, cuide da sua pele e encontre o presente perfeito em um só lugar!</p>
      <a href="<?= $baseUrl ?>/produtos" class="btn btn-primary py-2 mx-auto rounded-5 px-5 fw-medium mt-5">Ver Todos os Produtos</a>
    </div>
  </section>

  <!-- Categorias -->
  <section class="container-sm pt-112">
    <h2 class="display-6 text-center mb-4">Categorias</h2>
    <div class="row g-3 mx-auto items-grid">
      <?php foreach ($categorias as $categoria) : ?>
        <a href="<?= $baseUrl ?>/produtos?categoria=<?= $categoria['slug'] ?>" class="small categoria-item p-0 text-center col-6">
          <img
            src="assets/images/<?= $categoria['imagem'] ?>"
            alt="<?= $categoria['nome'] ?>"
            class="grid-item-imagem"
            onerror="this.onerror=null;this.src='assets/images/fallback.jpg';">
          <h6 class="w-100"><?= $categoria['nome'] ?></h6>
        </a>
      <?php endforeach; ?>
    </div>
  </section>
</main>