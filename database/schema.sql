
-- =========================================
-- LEVELUP STORE
-- Estrutura inicial do banco de dados
-- =========================================

CREATE DATABASE IF NOT EXISTS levelup_store
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE levelup_store;


-- =========================================
-- 1. TABELA DE USUARIOS
-- =========================================

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    tipo ENUM('cliente', 'admin') NOT NULL DEFAULT 'cliente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================================
-- 2. TABELA DE CATEGORIAS
-- =========================================

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL UNIQUE,
    descricao VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- =========================================
-- 3. TABELA DE PRODUTOS
-- =========================================

CREATE TABLE IF NOT EXISTS produtos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT UNSIGNED NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT UNSIGNED NOT NULL DEFAULT 0,
    imagem VARCHAR(255),
    ativo TINYINT(1) NOT NULL DEFAULT 1,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_produtos_categorias
        FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;


-- =========================================
-- 4. TABELA DE PEDIDOS
-- =========================================

CREATE TABLE IF NOT EXISTS pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    forma_pagamento ENUM('pix', 'boleto', 'cartao') NOT NULL,
    valor_total DECIMAL(10,2) NOT NULL,
    status ENUM('pendente', 'confirmado', 'cancelado')
        NOT NULL DEFAULT 'pendente',

    CONSTRAINT fk_pedidos_usuarios
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;


-- =========================================
-- 5. TABELA DE ITENS DO PEDIDO
-- =========================================

CREATE TABLE IF NOT EXISTS itens_pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    produto_id INT UNSIGNED NOT NULL,
    quantidade INT UNSIGNED NOT NULL,
    preco_unitario DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_itens_pedidos
        FOREIGN KEY (pedido_id)
        REFERENCES pedidos(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_itens_produtos
        FOREIGN KEY (produto_id)
        REFERENCES produtos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;


-- =========================================
-- DADOS INICIAIS DE TESTE
-- =========================================

INSERT INTO categorias (id, nome, descricao) VALUES
(1, 'Consoles', 'Consoles e videogames'),
(2, 'Perifericos', 'Teclados, mouses e headsets'),
(3, 'Hardware', 'Componentes para computadores'),
(4, 'Jogos', 'Jogos para diferentes plataformas'),
(5, 'Acessorios', 'Acessorios gamer e tecnologicos');


INSERT INTO produtos
(id, categoria_id, nome, descricao, preco, estoque, imagem)
VALUES
(1, 1, 'PlayStation 5',
 'Console de videogame de nova geracao.',
 3999.90, 10, 'assets/images/products/ps5.jpg'),

(2, 1, 'Xbox Series X',
 'Console de alto desempenho da Microsoft.',
 3599.90, 8, 'assets/images/products/xbox-series-x.jpg'),

(3, 2, 'Headset Gamer',
 'Headset com audio de alta qualidade.',
 299.90, 20, 'assets/images/products/headset.jpg'),

(4, 2, 'Mouse Gamer RGB',
 'Mouse gamer com iluminacao RGB.',
 149.90, 25, 'assets/images/products/mouse.jpg'),

(5, 3, 'Placa de Video RTX',
 'Placa de video para jogos e aplicacoes graficas.',
 2499.90, 5, 'assets/images/products/rtx.jpg'),

(6, 4, 'Jogo de Aventura',
 'Jogo de aventura para console.',
 249.90, 15, 'assets/images/products/game.jpg');
