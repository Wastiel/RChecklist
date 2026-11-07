CREATE DATABASE IF NOT EXISTS RChecklist CHARACTER SET utf8;
USE RChecklist;

CREATE TABLE checklist (
    id_checklist INT AUTO_INCREMENT PRIMARY KEY,
    titulo       VARCHAR(150) NOT NULL,
    descricao    TEXT,
    concluido    CHAR(1) NOT NULL DEFAULT 'N'    -- S = feito, N = pendente
);

INSERT INTO checklist (titulo, descricao) VALUES
('Criar o banco de dados', 'Rodar o script banco.sql no phpMyAdmin'),
('Configurar conexao.php', 'Conferir host, usuário e senha'),
('Abrir o sistema no navegador', 'Acessar http://localhost/RChecklist/');