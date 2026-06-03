<?php
$baseUrl = $baseUrl ?? '';

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Sobre', 'href' => $baseUrl . '/sobre'],
];
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <div class="sobre-intro d-flex flex-column gap-3">
      <span class="text-primary fw-medium small text-uppercase">Sobre a ManuMake</span>
      <h1 class="fw-light mb-0">Beleza, cuidado e autoestima em cada escolha</h1>
      <p class="text-body-secondary lh-lg mb-0">
        A ManuMake nasceu para aproximar você dos produtos de maquiagem, cosméticos e cuidados pessoais que combinam com a sua rotina.
        Nossa proposta é oferecer opções selecionadas com carinho, atendimento próximo e uma experiência de compra simples e acolhedora.
      </p>
      <p class="text-body-secondary lh-lg mb-0">
        Aqui, cada produto é pensado para realçar a beleza de um jeito leve, prático e acessível, seja para o dia a dia, para uma ocasião especial
        ou para presentear alguém querido.
      </p>
    </div>
  </section>

  <section class="container-sm">
    <div class="row g-3">
      <div class="col-12 col-md-4">
        <div class="sobre-info h-100 p-4">
          <i class="bi bi-heart text-primary"></i>
          <h2 class="fs-5 mt-3">Atendimento com carinho</h2>
          <p class="text-body-secondary mb-0">
            Valorizamos uma comunicação clara, próxima e cuidadosa em cada contato.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="sobre-info h-100 p-4">
          <i class="bi bi-bag-heart text-primary"></i>
          <h2 class="fs-5 mt-3">Produtos selecionados</h2>
          <p class="text-body-secondary mb-0">
            Reunimos itens de beleza e cuidados pessoais para diferentes estilos e necessidades.
          </p>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="sobre-info h-100 p-4">
          <i class="bi bi-stars text-primary"></i>
          <h2 class="fs-5 mt-3">Autoestima todos os dias</h2>
          <p class="text-body-secondary mb-0">
            Acreditamos que se cuidar também é uma forma de celebrar quem você é.
          </p>
        </div>
      </div>
    </div>
  </section>
</main>