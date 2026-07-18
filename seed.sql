INSERT INTO categoria (id, nome, slug, ordem, imagem)
VALUES
    (1, 'Body Splash', 'body-splash', 1, 'body-splash.jpg'),
    (2, 'Bolsas e Mochilas', 'bolsas-e-mochilas', 2, 'bolsas-e-mochilas.jpg'),
    (3, 'Cuidado para o Cabelo', 'cuidado-cabelo', 3, 'cuidado-cabelo.jpg'),
    (4, 'Cuidados para Pele', 'cuidados-pele', 4, 'cuidados-pele.jpg'),
    (5, 'Gloss Labial', 'gloss-labial', 5, 'gloss-labial.jpg'),
    (6, 'Kit para Presentear', 'kit-presentear', 6, 'kit-presentear.jpg'),
    (7, 'Maquiagem', 'maquiagem', 7, 'maquiagem.jpg'),
    (8, 'Variados', 'variados', 8, 'variados.jpg');

INSERT INTO produto (id, nome, descricao, preco, imagem, categoria_id)
VALUES
    (1, 'Spray Finalizador de Cachos', 'Finalizador capilar desenvolvido para definir e ativar os cachos, auxiliando no controle do frizz, brilho e maciez dos fios.', 24.99, '1.jpg', 3),
    (2, 'Protetor Térmico Rhenuks', 'Protetor térmico capilar Rhenuks. Auxilia na proteção da fibra capilar contra altas temperaturas do secador e chapinha, controlando o frizz.', 24.99, '2.jpg', 3),
    (3, 'Spray Finalizador Truss', 'Spray finalizador capilar Truss. Oferece proteção térmica contra ferramentas de calor, desembaraço dos fios e ação antifrizz.', 109.99, '3.jpg', 3),
    (4, 'Sabonete Líquido Corporal Hidratante', 'Sabonete líquido para higiene e limpeza corporal diária. Limpa suavemente, mantendo a pele perfumada.', 19.99, '4.jpg', 4),
    (5, 'Esfoliante Corporal Fenza', 'Esfoliante corporal Fenza. Auxilia na remoção de células mortas e impurezas da pele, promovendo suavidade e renovação.', 24.99, '5.jpg', 4),
    (6, 'Hidratante Facial Fenza Barbie', 'Creme hidratante facial Fenza da linha Barbie. Possui fórmula leve com rápida absorção, ajudando a manter a pele hidratada.', 19.99, '6.jpg', 4),
    (7, 'Loção Hidratante Corporal', 'Loção hidratante desenvolvida para cuidados corporais diários, auxiliando na nutrição e prevenção do ressecamento da pele.', 24.99, '7.jpg', 4),
    (8, 'Primer Facial Ruby Kisses', 'Primer facial Ruby Kisses. Prepara a pele para receber a maquiagem, suavizando a aparência de poros e controlando o brilho ao longo do dia.', 44.99, '8.jpg', 7),
    (9, 'Primer Facial Vizzela', 'Primer facial Vizzela. Auxilia na redução da aparência dos poros e no controle de oleosidade da pele, prolongando a duração da maquiagem.', 54.99, '9.jpg', 7),
    (10, 'Primer Facial Max Love', 'Primer facial Max Love formulado para preparar a pele, atenuando a oleosidade e ajudando na fixação da base.', 14.99, '10.jpg', 7),
    (11, 'Blindagem Dapop', 'Blindagem líquida facial Dapop. Aumenta a durabilidade da maquiagem, auxiliando na resistência à água e ao suor.', 19.99, '11.jpg', 7),
    (12, 'Bolsa de Ombro Feminina', 'Bolsa de ombro feminina com alças estruturadas e espaço interno ideal para transportar pertences pessoais.', 69.99, '12.jpg', 2),
    (13, 'Máscara de Cílios Trópico Ruby Rose', 'Máscara de cílios Trópico da Ruby Rose, com aplicador desenvolvido para destacar os cílios proporcionando volume.', 24.99, '13.jpg', 7),
    (14, 'Máscara de Cílios Melu', 'Máscara de cílios Melu da Ruby Rose. Proporciona alongamento, definição e efeito curvado aos cílios.', 24.99, '14.jpg', 7),
    (15, 'Máscara de Cílios Melu Rosa', 'Máscara de cílios Melu, indicada para dar definição e realce aos cílios.', 24.99, '15.jpg', 7),
    (16, 'Paleta de Sombras', 'Paleta contendo sombras com acabamentos variados e tonalidades adequadas para uso diário.', 24.99, '16.jpg', 7),
    (17, 'Bolsa Lateral Transversal', 'Bolsa lateral transversal equipada com alça ajustável e divisórias de fácil acesso para pertences diários.', 59.99, '17.jpg', 2),
    (18, 'Bolsa Tiracolo Compacta', 'Bolsa de ombro tiracolo com formato compacto e fecho seguro para transporte prático de itens indispensáveis.', 59.99, '18.jpg', 2),
    (19, 'Pó Facial Banana', 'Pó facial solto tipo Banana de textura fina, indicado para selar a maquiagem e auxiliar no controle do brilho da pele.', 15.00, '19.jpg', 7),
    (20, 'Máscara de Cílios Ruby Rose Trópico', 'Máscara de cílios Trópico da Ruby Rose, formulada para promover o alongamento e a definição de cada fio.', 24.99, '20.jpg', 7),
    (21, 'Máscara de Cílios Melu Laranja', 'Máscara de cílios Melu, focada em promover alongamento e curvatura aos fios.', 24.99, '21.jpg', 7),
    (22, 'Máscara de Cílios Melu Rosa', 'Máscara de cílios Melu, desenvolvida para realçar o volume dos cílios.', 24.99, '22.jpg', 7),
    (23, 'Sérum Facial Hidratante', 'Sérum facial de textura fluida e rápida absorção, indicado para a hidratação diária de todos os tipos de pele.', 24.99, '23.jpg', 4),
    (24, 'Base Líquida Bruna Tavares', 'Base líquida BT Skin de Bruna Tavares. Oferece cobertura uniforme com acabamento aveludado e fórmula com ativos de tratamento para a pele.', 84.99, '24.jpg', 7),
    (25, 'Gloss Labial Chocolate', 'Gloss labial com aroma e tonalidade suave de chocolate, proporcionando brilho luminoso e maciez aos lábios.', 15.00, '25.jpg', 5),
    (26, 'Bolsa de Mão Clássica', 'Bolsa de mão clássica com acabamento estruturado, compartimentos internos e alça resistente.', 79.99, '26.jpg', 2),
    (27, 'Body Splash Peony Dream', 'Body splash corporal com notas florais inspiradas no aroma de peônias, ideal para perfumação leve diária.', 39.99, '27.jpg', 1),
    (28, 'Body Splash Lady in Red', 'Body splash com fragrância refrescante que combina notas frutadas e adocicadas para uso diário.', 39.99, '28.jpg', 1),
    (29, 'Body Splash Rosé Glamour', 'Body splash leve com notas de rosas e toque floral para manter a pele perfumada.', 39.99, '29.jpg', 1),
    (30, 'Body Splash Bronze Goddess', 'Body splash corporal com aroma levemente quente, ideal para refrescar a pele ao longo do dia.', 56.99, '30.jpg', 1),
    (31, 'Kit Olympea Presente', 'Kit de presente inspirado em Olympea com um toque de elegância.', 69.99, '31.jpg', 6),
    (32, 'Gloss labial', 'Gloss labial de acabamento brilhante, formulado para hidratar e dar brilho aos lábios.', 19.99, '32.jpg', 5);

INSERT INTO caracteristica (nome)
VALUES
    ('Vegano'),
    ('Cruelty Free'),
    ('Hidratante'),
    ('Longa Duração'),
    ('Efeito Matte'),
    ('Brilho Intenso'),
    ('Proteção Térmica'),
    ('Compacto'),
    ('Premium'),
    ('Alta Pigmentação'),
    ('Perfumado'),
    ('Sem Parabenos'),
    ('Aço Inox'),
    ('Resistente à Água'),
    ('Uso Diário');

INSERT INTO produto_caracteristica (produto_id, caracteristica_id)
VALUES
    (1, 6),
    (1, 15),
    
    (2, 7),
    (2, 15),
    
    (3, 7),
    (3, 6),
    
    (4, 3),
    (4, 11),
    (4, 15),
    
    (6, 3),
    (6, 15),
    
    (7, 3),
    (7, 15),
    
    (8, 5),
    (8, 4),
    
    (9, 5),
    (9, 4),
    (9, 1),
    (9, 2),
    
    (10, 5),
    
    (11, 4),
    (11, 14),
    
    (13, 10),
    (13, 4),
    
    (14, 10),
    
    (15, 10),
    
    (16, 10),
    
    (19, 5),
    
    (20, 10),
    (20, 4),
    
    (21, 10),
    
    (22, 10),
    
    (23, 3),
    (23, 15),
    
    (24, 10),
    (24, 4),
    (24, 2),
    
    (25, 3),
    (25, 6),
    
    (27, 11),
    (27, 15),
    
    (28, 11),
    (28, 15),
    
    (29, 11),
    (29, 15),
    
    (30, 11),
    (30, 15),
    
    (31, 11),
    
    (32, 3),
    (32, 6);

INSERT INTO usuario (usuario, senha)
VALUES
    ('admin', '$2y$10$XrJ8oSYnxpwyb2w6l5ReBumPE8TYiVGjZLNaoy06hzzS7lZGUGJF6');