CREATE TABLE categoria (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    ordem INT UNSIGNED NOT NULL DEFAULT 0,
    imagem VARCHAR(200) NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE categoria ADD UNIQUE categoria_slug_unique(slug);

CREATE TABLE produto (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    imagem VARCHAR(200) NULL,
    categoria_id INT UNSIGNED NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE caracteristica (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE produto_caracteristica(
    produto_id INT UNSIGNED NOT NULL,
    caracteristica_id INT UNSIGNED NOT NULL,
    PRIMARY KEY(produto_id, caracteristica_id)
);

CREATE TABLE usuario (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE usuario ADD UNIQUE usuario_usuario_unique(usuario);

ALTER TABLE produto_caracteristica
    ADD CONSTRAINT produto_caracteristica_caracteristica_id_foreign FOREIGN KEY(caracteristica_id)
    REFERENCES caracteristica(id);
ALTER TABLE produto_caracteristica
    ADD CONSTRAINT produto_caracteristica_produto_id_foreign FOREIGN KEY(produto_id)
    REFERENCES produto(id);
ALTER TABLE produto
    ADD CONSTRAINT produto_categoria_id_foreign FOREIGN KEY(categoria_id)
    REFERENCES categoria(id);

ALTER TABLE produto
ADD INDEX produto_categoria_id_index (categoria_id);