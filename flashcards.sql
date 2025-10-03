-- Criar banco de dados
CREATE DATABASE flashcards_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Usar o banco
USE flashcards_db;

-- Tabela de matérias
CREATE TABLE materias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE
);

-- Tabela de flashcards
CREATE TABLE flashcards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    materia_id INT NOT NULL,
    pergunta TEXT NOT NULL,
    resposta TEXT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);
