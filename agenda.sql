-- Criar banco de dados
CREATE DATABASE agenda_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar banco
USE agenda_db;

-- Tabela de compromissos (um por dia/horário)
CREATE TABLE compromissos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dia_semana ENUM('segunda','terca','quarta','quinta','sexta','sabado','domingo') NOT NULL,
    compromisso VARCHAR(255) NOT NULL,
    horario TIME NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de notas gerais
CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conteudo TEXT NOT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
