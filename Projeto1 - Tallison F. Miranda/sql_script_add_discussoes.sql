CREATE TABLE IF NOT EXISTS postagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    texto VARCHAR(450) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    FOREIGN KEY (id_usuario) REFERENCES jogadores(id)
);

CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_postagem INT NOT NULL,
    id_usuario INT NOT NULL,
    texto VARCHAR(300) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
    FOREIGN KEY (id_postagem) REFERENCES postagens(id),
    FOREIGN KEY (id_usuario) REFERENCES jogadores(id)
);

ALTER TABLE jogadores ADD COLUMN email VARCHAR(255) NOT NULL;
