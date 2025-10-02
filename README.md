# Sistema Escolar - Autenticação e Controle de Acesso

## Descrição do Projeto
Este projeto em PHP implementa um sistema escolar com autenticação segura de usuários via login e senha, proteção de páginas internas por sessão e perfis de usuário, além de mensagens claras para erros e sucessos.

---

## Requisitos

- PHP 7.4 ou superior
- Servidor Web (localhost)
- MySQL / PhpMyAdmin
- Extensão PDO para PHP
- Navegador moderno

---

## Banco de Dados

- Usado MySQL (localhost/phpmyadmin) com PDO e prepared statements para segurança.
- Tabela principal: `usuarios`

Estrutura mínima da tabela `usuarios`:

| Campo       | Tipo           | Comentário                      |
|-------------|----------------|---------------------------------|
| id          | INT (PK, AI)   | Identificador único do usuário  |
| tipo        | ENUM           | Admin, Professor, Aluno         |
| nome        | VARCHAR(255)   | Nome do usuário                 |
| cpf         | VARCHAR        | CPF do usuário                  |
| matricula   | VARCHAR        | Matrícula do usuário            |
| email       | VARCHAR        | E-mail do usuário               |
| nome_pai    | VARCHAR        | Nome do pai                     |
| nome_mae    | VARCHAR        | Nome da mae                     |
| data_nascim | VARCHAR        | Data nascimento do usuario      |
| senha_hash  | VARCHAR        | Senha has                       |


> O arquivo `banco.sql` contém o script para criar a tabela e inserir um usuário de teste com a senha já hasheada.

---

## Usuário de Teste

| Matricula           | Senha          | Perfil |
|---------------------|----------------|--------|
| 231-000655          | 123456@abcdef  | Admin  |


Obs.: A senha foi criada nesse padrão para atender aos critérios de segurança atuais.
---

## Estrutura e Funcionalidades dos Arquivos

### 1. `index.php`

- Página de login com formulário de matricula ou CPF (somente para alunos) e senha.
- Exibe mensagens de erro ou sucesso baseadas em sessão.
- Inclui `index_script.js` para validação dos campos e outras funções para otimizar a página de login.
- Não permite redirecionamento automático ao dashboard (login manual).
- Input de matricula ou CPF (somente para alunos) e senha com validação e feedback.
- Existe um botão "Esqueceu sua senha?" somente para efeito visual, nada foi implementado nele ainda (implementações serão feitas nas próximas entregas depois de vermos mais conteúdos).

### 2. `index_script.js`

- Valida campos de login e senha no cliente.
- Habilita botão "Entrar" somente se campos estiverem válidos.
- Mostra mensagens específicas para formatos inválidos.
- Controle para toggle mostrar/esconder senha.

### 3. `autentica.php`

- Recebe dados do formulário via POST.
- Sanitiza e valida os dados.
- Consulta banco para verificar credenciais via PDO e prepared statements.
- Usa `password_verify` para comparar senha digitada com `senha_hash`.
- Em caso de sucesso:
  - Inicia sessão.
  - Regenera ID da sessão (`session_regenerate_id(true)`).
  - Define `$_SESSION['usuario']` e `$_SESSION['perfil']`.
  - Redireciona para `dashboard.php`.
- Em caso de falha:
  - Incrementa contador de tentativas na sessão.
  - Exibe mensagem amigável de erro.
  - Bloqueia login após 5 tentativas (pode não estar 100% funcional).

### 4. `verifica_sessao.php`

- Arquivo guard para proteção de páginas internas.
- Verifica se `$_SESSION['usuario']` existe; caso contrário, redireciona para `index.php`.
- Verifica se o perfil do usuário é permitido na página (quando aplicável).
- Redireciona para `sem_permissao.php` em caso de acesso não autorizado.

### 5. `dashboard.php`

- Página restrita.
- Inclui `verifica_sessao.php` no topo para proteção.
- Exibe mensagem de boas-vindas com o nome do usuário logado.
- Link para logout.

### 6. `logout.php`

- Encerra a sessão (`session_unset()` e `session_destroy()`).
- Redireciona para `index.php`.

### 7. `sem_permissao.php`

- Página exibida quando o usuário tenta acessar conteúdo não permitido.
- Mensagem clara de "Acesso Negado".

### 8. Arquivos de Cadastro (Opcional/Complementares)

- `cadastro_usuarios.php` — formulário para cadastrar novos usuários (alunos, professores, admins).
- `processa_cadastro.php` — processa e insere usuário no banco com senha hasheada.
- `cadastro_script.js` — validações no cadastro e funções para validar os campos durante o cadastro.
- `cadastro_sucesso.php` — confirmação do cadastro.

### 9. Conexão e Configuração

- `conexao.php` — arquivo para conexão com banco via PDO.
- `verifica_sessao.php` — script para proteger páginas restritas.

### 10. Outros

- `dashboard_admin_script.js` — script para dashboard do administrador para fazer validações de campos, funções e cookies da página.
- `style.css` — estilos gerais da aplicação.

---

## Como Rodar o Projeto

1. **Configurar banco de dados:**
   - Importe o arquivo `banco.sql` no seu MySQL ou no localhost/phpmyadmin.
   - Atualize as credenciais em `conexao.php` para conectar ao seu banco.

2. **Configurar servidor:**
   - Coloque os arquivos em seu servidor web.
   - Certifique-se que PHP e PDO estejam habilitados.

3. **Acessar a aplicação:**
   - Abra o navegador em `index.php`.
   - Faça login com o usuário de teste.
   - Teste funcionalidades e restrições.

---

## Fluxo Implementado

1. Usuário acessa `index.php` (formulário de login).
2. Usuário digita matricula ou CPF (somente alunos) e senha.
3. `autentica.php` valida as credenciais:
   - Se corretas, inicia sessão e redireciona para `dashboard.php`.
   - Se incorretas, exibe mensagem de erro, controla tentativas.
4. `dashboard.php` é acessível apenas após login com sessão ativa.
5. Qualquer tentativa de acessar páginas restritas sem sessão válida:
   - Redireciona para `index.php`.
6. Se o perfil do usuário não tem permissão para determinada página:
   - Redireciona para `sem_permissao.php`.
7. Usuário pode fazer logout via `logout.php`.

---

## Segurança & Boas Práticas

- Senhas armazenadas com `password_hash`.
- Verificação com `password_verify`.
- Uso de PDO com prepared statements para prevenir SQL Injection.
- `session_regenerate_id(true)` após login para mitigar sequestro de sessão.
- Mensagens amigáveis para o usuário.
- Controle de tentativas de login para evitar brute force.
- Sanitização e escape ao exibir dados.

---

## Comentários extras:

- Foram implementadas telas bem básicas para os alunos, admins e professores, servindo apenas como um "esboço" já que o foco dessa entrega é somente
a tela de login. Portanto, esses dashboards criados para os alunos, professores e admins serão melhores implementados quando for solicitado a entrega
mais robusta desses itens.
