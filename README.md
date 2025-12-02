# 🏫 Sistema Escolar — Autenticação, Controle de Acesso e Instalação Completa

Este documento combina **o guia técnico de autenticação e controle de acesso** com **o passo a passo completo de instalação e estrutura do sistema**.
O objetivo é que qualquer desenvolvedor ou avaliador consiga instalar, executar e compreender toda a lógica do **Sistema Escolar em PHP**.

---

## 📘 Descrição do Projeto

O **Sistema Escolar** é uma aplicação desenvolvida em PHP com autenticação segura, controle de sessões, proteção de páginas internas e perfis de usuário.
Ele implementa mensagens claras de erro/sucesso e organiza o código de forma modular, utilizando **PDO**, **prepared statements** e boas práticas de segurança.

A aplicação foi projetada para rodar localmente com **XAMPP**, utilizando o **MySQL** como banco de dados.

Além do componente Web, o projeto inclui uma **API REST segura**, estruturada em controllers separados, permitindo que futuras aplicações (mobile, dashboards externos, integrações) consumam os dados diretamente.

---

## ⚙️ Requisitos

* PHP 7.4 ou superior
* XAMPP (Apache e MySQL ativos)
* PhpMyAdmin
* Extensão PDO habilitada
* Navegador moderno (Chrome, Firefox, Edge, etc.)
* Editor de código (ex.: VS Code)

---

## 🗄️ Banco de Dados

* Banco: **escola**
* Sistema: **MySQL (via localhost/phpmyadmin)**
* Conexão via **PDO** com **prepared statements**
* Arquivo de referência: `app/banco.sql`

### Estrutura mínima da tabela `usuarios`

| Campo       | Tipo         | Comentário                       |
| ----------- | ------------ | -------------------------------- |
| id          | INT (PK, AI) | Identificador único              |
| tipo        | ENUM         | Admin, Professor, Aluno          |
| nome        | VARCHAR(255) | Nome completo                    |
| cpf         | VARCHAR      | CPF do usuário                   |
| matricula   | VARCHAR      | Matrícula institucional          |
| email       | VARCHAR      | E-mail do usuário                |
| nome_pai    | VARCHAR      | Nome do pai                      |
| nome_mae    | VARCHAR      | Nome da mãe                      |
| data_nascim | VARCHAR      | Data de nascimento               |
| senha_hash  | VARCHAR      | Senha hasheada (`password_hash`) |

### Estrutura mínima da tabela `notas`

| Campo         | Tipo         | Comentário                |
| ------------- | ------------ | ------------------------- |
| id            | INT PK AI    | Identificador único       |
| aluno_id      | INT FK       | Relacionado a usuarios.id |
| nota_final    | DECIMAL(5,2) | Nota final                |
| status        | VARCHAR(50)  | Aprovado / Reprovado      |
| data_registro | DATETIME     | Data e hora do registro   |

---

## 👤 Usuário de Teste

| Matrícula  | Senha         | Perfil |
| ---------- | ------------- | ------ |
| 231-000655 | 123456@abcdef | Admin  |

---

## 🧩 Estrutura do Projeto

```
Projeto_teste2/
├── app/
│   └── banco.sql
├── api/
│   ├── config.php        
│   ├── index.php         
│   ├── Response.php      
│   ├── Auth.php          
│   ├── AuthController.php
│   ├── AlunoController.php
│   └── NotasController.php
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── index_script.js
│       ├── cadastro_script.js
│       └── dashboard_admin_script.js
└── public/
    ├── autentica.php
    ├── conexao.php
    ├── dashboard.php
    ├── dashboard_aluno.php
    ├── dashboard_professor.php
    ├── cadastro_usuarios.php
    ├── cadastro_sucesso.php
    ├── processa_cadastro.php
    ├── verifica_sessao.php
    ├── sem_permissao.php
    ├── logout.php
```

---

## 🧭 Instalação Passo a Passo

### 1️⃣ Clonar o Repositório

```bash
cd C:\xampp\htdocs
git clone https://github.com/seu-usuario/seu-repositorio.git Projeto_teste2
```

### 2️⃣ Importar o Banco de Dados

1. Inicie **Apache** e **MySQL** pelo painel do XAMPP.
2. Acesse: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
3. Crie um banco chamado **escola**.
4. Vá em *Importar* → Selecione `app/banco.sql` → *Executar*.

### 3️⃣ Configurar Conexão

Abra `public/conexao.php` e confira os parâmetros:

```php
<?php
$host = 'localhost';
$db   = 'escola';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
```

### 4️⃣ Executar o Sistema

Acesse:

```
http://localhost/Projeto_teste2/index.php
```

Faça login com as credenciais de teste.

---

## 🧠 Estrutura e Funcionalidades dos Arquivos

### 🔹 `index.php`

* Página inicial de login.
* Campos de **matrícula ou CPF** e **senha**.
* Exibe mensagens de erro/sucesso.
* Inclui validações via `index_script.js`.
* Contém botão visual “Esqueceu sua senha?” (não funcional ainda).

### 🔹 `index_script.js`

* Validação de campos e feedbacks em tempo real.
* Habilita botão “Entrar” apenas se os campos forem válidos.
* Função para mostrar/ocultar senha.

### 🔹 `autentica.php`

* Recebe dados via `POST`.
* Sanitiza e valida.
* Consulta banco com `PDO` e prepared statements.
* Usa `password_verify` para autenticação segura.
* Cria sessão e redireciona para o dashboard correspondente ao perfil.
* Implementa contador de tentativas e bloqueio após 5 erros.

### 🔹 `verifica_sessao.php`

* Protege páginas internas.
* Verifica se `$_SESSION['usuario']` existe.
* Redireciona para `index.php` se a sessão estiver expirada.
* Impede acesso de perfis não permitidos (`sem_permissao.php`).

### 🔹 Dashboards

* `dashboard.php` – Admin
* `dashboard_aluno.php`
* `dashboard_professor.php`

### 🔹 `logout.php`

* Finaliza sessão com segurança.

---

## 🌐 API REST — Documentação Oficial

### 🔹 Estrutura da API (`/api/`)

* `index.php` – roteador
* `AuthController.php` – login seguro
* `AlunoController.php` – dados dos alunos
* `NotasController.php` – notas e rendimento
* `Auth.php` – gerencia sessão e usuário logado
* `Response.php` – respostas JSON padronizadas

### 🔑 Rotas da API

**POST /api/index.php?rota=login**

Autentica o usuário.

Entrada:
```json
{
  "matricula": "231-000655",
  "senha": "123456@abcdef"
}
```

Saída:
```json
{
  "status": 200,
  "msg": "Login OK",
  "data": {
    "id": 1,
    "nome": "Admin",
    "tipo": "Admin"
  }
}
```

**GET /api/index.php?rota=alunos** – Lista alunos.

**GET /api/index.php?rota=alunos/{id}** – Retorna dados do aluno + notas.

**GET /api/index.php?rota=notas** – Lista todas as notas cadastradas.

---

## 🔒 Segurança e Boas Práticas

* `password_hash()` e `password_verify()`
* Sessão regenerada pós-login
* SQL com prepared statements
* Controle de sessão em todas as páginas internas
* Proteção contra brute-force
* Sanitização de entradas
* Erros não revelam detalhes sensíveis

---

## 🔁 Fluxo de Autenticação (Web)

1. Usuário envia matrícula + senha
2. Validado com `password_verify()`
3. Sessão é criada e ID regenerado
4. Usuário é redirecionado para o dashboard do seu perfil
5. Sessão expira após período definido
6. Logout limpa sessão com segurança

---

## 🌐 Fluxo da Autenticação via API

* Cliente (app / JS / serviço externo) envia JSON
* API busca usuário pela matrícula
* `password_verify()` compara senha enviada com hash
* Se válido → retorna dados essenciais
* Se inválido → retorna HTTP 401
* Sessão é automaticamente vinculada ao request se necessário

---

## 📋 Observações para Professores

* Podem logar via matrícula ou CPF
* Apenas páginas específicas são liberadas
* Tentativas incorretas são contabilizadas
* Acesso negado redireciona para `sem_permissao.php`

---

## 🧩 Problemas Comuns & Soluções

| Problema                    | Solução                                                     |
| --------------------------- | ----------------------------------------------------------- |
| Página em branco / erro 500 | Habilite `display_errors=On` no `php.ini`                   |
| Banco não conecta           | Verifique `conexao.php`, MySQL ativo e credenciais corretas |
| CSS não carrega             | Confirme o caminho relativo `assets/css/style.css`          |
| Sessão expira rápido        | Ajuste `$timeout` em `verifica_sessao.php`                  |
| Login não funciona          | Verifique hash no banco e campos `matricula`/`senha`        |

---

## 💡 Boas Práticas Extras

* Mantenha `banco.sql` atualizado.
* Adicione `.gitignore` para excluir arquivos sensíveis.
* Crie backups periódicos do banco.
* Documente novas funções diretamente no README ou Wiki do projeto.

---

## 🤝 Como Contribuir

1. Faça um fork do projeto.
2. Crie uma nova branch: `git checkout -b feature/nova-funcionalidade`.
3. Realize commits descritivos.
4. Envie um Pull Request com resumo das alterações.

---

## 📜 Licença

Projeto aberto para uso acadêmico e aprendizado.
Pode ser distribuído sob a licença **MIT** (recomendado).
Adicione o arquivo `LICENSE` se desejar formalizar.

---

## 📬 Contato e Suporte

Para dúvidas, suporte técnico ou aprimoramentos, entre em contato pelo repositório GitHub ou envie mensagem com o título:
**"Suporte Sistema Escolar - Autenticação"**

---

> 📖 **Nota final:** Este projeto está em fase inicial. As telas de alunos, professores e administradores são versões básicas que serão evoluídas em futuras entregas, conforme novos módulos forem implementados (relatórios, notas, permissões e cadastros avançados).

