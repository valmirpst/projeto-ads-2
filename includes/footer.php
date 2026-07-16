<?php
require_once __DIR__ . '/../config/config.php';
$baseUrl = $baseUrl ?? '';
?>

<footer class="footer mt-80">
  <div class="container py-5">
    <div class="row gy-4">
      <!-- Coluna 1: Marca e Bio -->
      <div class="col-12 col-lg-4 pe-lg-5">
        <a class="d-inline-block mb-3" href="<?= $baseUrl ?>/">
          <img src="<?= $baseUrl ?>/assets/images/ManuMakeLogoSemFundo.png" alt="Logo ManuMake" class="footer-logo">
        </a>
        <p class="text-body-secondary footer-bio">
          Realçando a sua beleza e garantindo qualidade em cada detalhe. Encontre os melhores produtos para a sua rotina de cuidados.
        </p>
      </div>

      <!-- Coluna 2: Links Rápidos -->
      <div class="col-12 col-sm-6 col-lg-2 offset-lg-1">
        <h6 class="footer-heading mb-3">Explorar</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
          <li><a class="footer-link" href="<?= $baseUrl ?>/">Início</a></li>
          <li><a class="footer-link" href="<?= $baseUrl ?>/produtos">Produtos</a></li>
          <li><a class="footer-link" href="<?= $baseUrl ?>/admin">Admin</a></li>
        </ul>
      </div>

      <!-- Coluna 3: Institucional e Contato -->
      <div class="col-12 col-sm-6 col-lg-3">
        <h6 class="footer-heading mb-3">Institucional</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 mb-0">
          <li><a class="footer-link" href="<?= $baseUrl ?>/sobre">Sobre Nós</a></li>
          <li><a class="footer-link" href="<?= $baseUrl ?>/termos">Termos de Uso</a></li>
        </ul>
      </div>

      <!-- Coluna 4: Redes Sociais -->
      <div class="col-12 col-lg-2">
        <h6 class="footer-heading mb-3">Siga-nos</h6>
        <div class="d-flex gap-3 footer-socials">
          <a class="footer-social-icon" href="<?= INSTAGRAM_URL ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
          <a class="footer-social-icon" href="https://wa.me/<?= WHATSAPP_NUMERO ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
            <i class="bi bi-whatsapp"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Copyright Bar -->
  <div class="footer-bottom border-top border-primary-lighter py-3">
    <div class="container text-center text-lg-start d-lg-flex justify-content-between align-items-center">
      <p class="text-muted small mb-0">© 2026 ManuMake - Todos os direitos reservados.</p>
      <p class="text-muted small mb-0 mt-2 mt-lg-0">Feito com <i class="bi bi-heart-fill text-primary" style="font-size: 0.75rem;"></i> para realçar sua beleza.</p>
    </div>
  </div>
</footer>

<!-- WhatsApp Float Button -->
<a href="https://wa.me/<?= WHATSAPP_NUMERO ?>" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="Falar no WhatsApp" title="Dúvidas? Fale conosco!">
  <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>