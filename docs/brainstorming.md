# Site catálogo de produtos

### ✔️ Visão geral do projeto

- Página inicial com categorias
- Listagem de produtos (READ do banco)
- Filtro por categoria (opcional, mas agrega valor)
- Página de detalhes do produto
- Botão “Tenho interesse” → abre WhatsApp com mensagem pronta

---

### ✔️ Exemplo de fluxo (PHP + MySQL)

- index.php → lista produtos
- produto.php?id=1 → detalhes do produto
- conexao.php → conexão com banco
- header/footer → reaproveitamento de layout

---

### ✔️ Estrutura básica do banco (MySQL)

#### Tabela: categorias

- id
- nome

#### Tabela: subcategorias (opcional)

- id
- nome
- id_categoria (FK)

#### Tabela: produtos

- id
- nome
- descricao
- preco
- imagem (caminho do arquivo)
- id_categoria (FK)

---

### ✔️ Integração com WhatsApp (ponto forte do projeto)

$mensagem = urlencode("Olá, tenho interesse no produto: $nomeProduto");
$link = "https://wa.me/55SEUNUMERO?text=$mensagem";

---

### ✔️ Diferenciais simples que impressionam

- Filtro por categoria (WHERE id_categoria = ?)
- Busca por nome (LIKE '%texto%')
- Layout responsivo (mesmo básico)
- Mensagem personalizada com nome do produto no WhatsApp

---

### ✔️ Justificativa do projeto

- Cliente real
- Problema real (divulgação dos produtos)
- Solução funcional
- Uso de PHP + MySQL
- CRUD parcial (READ implementado, restante opcional)

---

### ✔️ Estrutura simples do projeto

<pre>
/produtos-site
│
├── /assets
│   ├── /css
│   │   └── style.css
│   ├── /js
│   │   └── script.js
│   └── /img
│       └── (imagens fixas do site, ex: logo)
│
├── /uploads
│   └── (imagens dos produtos vindas do banco)
│
├── /config
│   └── conexao.php
│
├── /includes
│   ├── header.php
│   └── footer.php
│
├── /pages
│   └── produto.php
│
├── index.php
└── .htaccess (opcional)
</pre>
