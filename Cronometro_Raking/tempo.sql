CREATE TABLE IF NOT EXISTS tempos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tempo VARCHAR(20) NOT NULL,     -- tempo como string "HH:MM:SS" ou até "DDD:HH:MM:SS"
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
