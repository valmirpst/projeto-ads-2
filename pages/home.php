<?php
$categorias = [
  ['nome' => 'Body Splash', 'imagem' => 'body-splash.jpg'],
  ['nome' => 'Bolsas e Mochilas', 'imagem' => 'bolsas-mochilas.jpg'],
  ['nome' => 'Copos e Garrafas Térmicas', 'imagem' => 'copos-garrafas-termicas.jpg'],
  ['nome' => 'Cuidado para o Cabelo', 'imagem' => 'cuidado-cabelo.jpg'],
  ['nome' => 'Cuidado com a Pele', 'imagem' => 'cuidado-pele.jpg'],
  ['nome' => 'Gloss Labial', 'imagem' => 'gloss-labial.jpg'],
  ['nome' => 'Kits para Presentes', 'imagem' => 'kits-presentes.jpg'],
  ['nome' => 'Maquiagem', 'imagem' => 'maquiagem.jpg'],

]
?>


<link rel="stylesheet" href="assets/css/pages/home.css">
<main class="flex-grow-1 d-flex flex-column gap-5">
  <!-- Hero -->
  <section class="container text-center pt-112">
    <div class="hero-content mx-auto d-flex flex-column gap-3">
      <h1 class="display-1 ">BELEZA QUE IMPRESSIONA</h1>
      <p class="hero-subtitle fw-light mb-4">Realce sua essência, cuide da sua pele e encontre o presente perfeito em um só lugar!</p>
      <a href="?pagina=produtos" class="btn btn-primary mx-auto rounded-5 px-5 fw-medium mt-5">Ver Todos os Produtos</a>
    </div>
  </section>

  <!-- Categorias -->
  <section class="container pt-112">
    <h2 class="display-6 text-center mb-4">Categorias</h2>
    <div class="row g-3 mx-auto">
      <?php foreach ($categorias as $categoria) : ?>
        <div class="col-6">
          <img src="assets/images/<?= $categoria['imagem'] ?>" alt="<?= $categoria['nome'] ?>" class="categoria-imagem rounded-4">
          <h3><?= $categoria['nome'] ?></h3>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>