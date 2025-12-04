-- Crie o banco (ajuste o nome se quiser)
CREATE DATABASE IF NOT EXISTS escola
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE escola;

-- Tabela de usuários
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  tipo            ENUM('administrador', 'professor', 'aluno') NOT NULL,
  nome            VARCHAR(150) NOT NULL,
  cpf             VARCHAR(14)  NOT NULL,
  matricula       VARCHAR(30)  NOT NULL,
  email           VARCHAR(150) NOT NULL,
  nome_pai        VARCHAR(150) NULL,
  nome_mae        VARCHAR(150) NULL,
  data_nascimento DATE         NULL,
  senha_hash      VARCHAR(255) NOT NULL,
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_cpf (cpf),
  UNIQUE KEY uq_matricula (matricula),
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuário administrador de teste
-- Login: matricula = admin001
-- Senha: Admin@123  (exemplo - hash já gerado)
INSERT INTO usuarios (tipo, nome, cpf, matricula, email, data_nascimento, senha_hash) VALUES
('administrador', 'Administrador do Sistema', '696.969.696-00', 'admin001', 'admin@example.com', '1990-01-01',
 '$2y$12$g2MYz45VDD/B.Uv7J3mqV.VYmOUnkROeTN96bi7Gce.VHXm0Pecdi');

-- (Opcional) Exemplo de aluno para testes
INSERT INTO usuarios (tipo, nome, cpf, matricula, email, data_nascimento, senha_hash) VALUES
('aluno', 'Aluno Teste', '111.111.111-11', '2025-0001', 'aluno@example.com', '2005-05-05',
 '$2y$12$examplehashtestexamplehashtestexamplehashtestexamp');

-- Tabela para tokens de login persistente
DROP TABLE IF EXISTS tokens_login;
CREATE TABLE tokens_login (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NOT NULL,
  token CHAR(64) NOT NULL,
  expiracao DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  UNIQUE KEY uq_token (token),
  INDEX idx_usuario_token (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de notas (compatível com os seus scripts PHP)
DROP TABLE IF EXISTS notas;
CREATE TABLE notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aluno_id INT NOT NULL,
    nota_final FLOAT NOT NULL,
    status ENUM('Aprovado', 'Reprovado') NOT NULL,
    data_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (aluno_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_aluno_id (aluno_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- VIEW que resume média e situação por aluno
DROP VIEW IF EXISTS vw_alunos_status;
CREATE OR REPLACE VIEW vw_alunos_status AS
SELECT 
    u.id AS aluno_id,
    u.nome AS nome,
    u.matricula AS matricula,
    u.email AS email,
    AVG(n.nota_final) AS media,
    CASE 
        WHEN AVG(n.nota_final) IS NULL THEN 'SEM NOTAS'
        WHEN AVG(n.nota_final) >= 6 THEN 'APROVADO'
        ELSE 'REPROVADO'
    END AS status
FROM usuarios u
LEFT JOIN notas n ON n.aluno_id = u.id
WHERE u.tipo = 'aluno'
GROUP BY u.id, u.nome, u.matricula, u.email;
