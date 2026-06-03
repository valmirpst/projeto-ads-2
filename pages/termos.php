<?php
$baseUrl = $baseUrl ?? '';

$breadcrumbs = [
  ['name' => 'Início', 'href' => $baseUrl . '/'],
  ['name' => 'Termos de Uso', 'href' => $baseUrl . '/termos'],
];
?>

<?php require_once __DIR__ . '/../includes/breadcrumb.php'; ?>

<main class="container flex-grow-1 d-flex flex-column gap-5">
  <section class="container-sm pt-4">
    <div class="termos-intro d-flex flex-column gap-3">
      <span class="text-primary fw-medium small text-uppercase">Termos de Uso</span>
      <h1 class="fw-light mb-0">Uso do site ManuMake</h1>
      <p class="text-body-secondary lh-lg mb-0">
        Esta página reúne informações simples sobre o uso do site. Ao navegar por aqui, você entende que os produtos, valores e informações
        apresentados podem ser atualizados conforme necessidade da loja.
      </p>
    </div>
  </section>

  <section class="container-sm">
    <div class="termos-content d-flex flex-column gap-4">
      <div class="termos-item p-4">
        <h2 class="fs-5">Informações dos produtos</h2>
        <p class="text-body-secondary mb-0">
          As imagens, descrições, preços e disponibilidade dos produtos podem mudar. Antes de concluir uma compra, confirme as informações
          diretamente com a ManuMake.
        </p>
      </div>

      <div class="termos-item p-4">
        <h2 class="fs-5">Contato e atendimento</h2>
        <p class="text-body-secondary mb-0">
          O site ajuda você a conhecer os produtos e entrar em contato com a loja. Dúvidas sobre pedidos, pagamentos ou entregas devem ser
          combinadas pelos canais oficiais de atendimento.
        </p>
      </div>

      <div class="termos-item p-4">
        <h2 class="fs-5">Uso adequado</h2>
        <p class="text-body-secondary mb-0">
          Use o site de forma respeitosa, sem tentar prejudicar seu funcionamento ou usar indevidamente os conteúdos, imagens e as informações disponíveis.
        </p>
      </div>
    </div>
  </section>
</main>