-- Tabela única para Amizades e Sugestões
CREATE TABLE IF NOT EXISTS amizades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario1 INT NOT NULL,      -- Quem fez a ação (amizade ou sugestão)
    id_usuario2 INT NOT NULL,      -- Quem recebeu
    tipo ENUM('amizade','sugestao') NOT NULL, -- Define se é amizade ou sugestão
    status ENUM('pendente','aceito') DEFAULT 'pendente', -- Para amizades
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_relacao_usuario1 FOREIGN KEY (id_usuario1) REFERENCES usuarios(id) ON DELETE CASCADE,
    CONSTRAINT fk_relacao_usuario2 FOREIGN KEY (id_usuario2) REFERENCES usuarios(id) ON DELETE CASCADE
);