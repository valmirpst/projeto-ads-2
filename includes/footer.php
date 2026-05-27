<?php
$baseUrl = $baseUrl ?? '';
?>

<footer class="footer px-2 px-lg-4 pt-5 pb-2 mt-80">
  <a class="pb-3" href="<?= $baseUrl ?>/">
    <img src="assets/images/ManuMakeLogoSemFundo.png" alt="Logo">
  </a>

  <div class="d-flex flex-wrap gap-5 mt-4 px-3 py-3">
    <div class="d-flex flex-column gap-2">
      <h6>Contato</h6>
      <a class="link-body-emphasis link-underline-opacity-50" href="<?= $baseUrl ?>/sobre">Sobre Nós</a>
      <a class="link-body-emphasis link-underline-opacity-50" href="https://www.instagram.com/manumake/" target="_blank" rel="noopener noreferrer">Instagram</a>
      <a class="link-body-emphasis link-underline-opacity-50" href="<?= $baseUrl ?>/termos">Termos de Uso</a>
    </div>
    <div class="d-flex flex-column gap-2">
      <h6>Explorar</h6>
      <a class="link-body-emphasis link-underline-opacity-50" href="<?= $baseUrl ?>/">Início</a>
      <a class="link-body-emphasis link-underline-opacity-50" href="<?= $baseUrl ?>/produtos">Produtos</a>
      <span title="Área Administrativa (Em Desenvolvimento...)">Admin</span>
    </div>
  </div>

  <div class="px-3 pt-3">
    <p class="text-muted small">© 2026 - Todos os direitos reservados</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>