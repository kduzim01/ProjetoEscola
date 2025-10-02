<?php
require_once 'conexao.php';
$PERFIS_PERMITIDOS = ['administrador']; // apenas admin
require_once 'verifica_sessao.php';

// ⛔️ Função redir (usada só para ERROS)
function redir($ok, $msg) {
    session_start();
    if ($ok) {
        $_SESSION['msg'] = $msg;
    } else {
        $_SESSION['erro'] = $msg;
    }
    header("Location: cadastro_usuarios.php");
    exit;
}

// 📥 Dados do POST
$tipo       = $_POST['tipo'] ?? '';
$nome       = trim($_POST['nome'] ?? '');
$cpf        = trim($_POST['cpf'] ?? '');
$senha      = $_POST['senha'] ?? '';
$senha2     = $_POST['senha2'] ?? '';
$matricula  = null;
$email      = null;
$data_nascimento = null;

// 🔍 Validação inicial
if (!$tipo || !$nome || !$cpf || !$senha || !$senha2) {
    redir(false, 'Preencha todos os campos obrigatórios.');
}

if ($senha !== $senha2) {
    redir(false, 'As senhas não conferem.');
}

$tiposValidos = ['aluno', 'professor', 'administrador'];
if (!in_array($tipo, $tiposValidos, true)) {
    redir(false, 'Tipo de usuário inválido.');
}

// 📌 Campos adicionais
$nome_pai = trim($_POST['nome_pai'] ?? '') ?: null;
$nome_mae = trim($_POST['nome_mae'] ?? '') ?: null;

if ($tipo === 'aluno') {
    $email           = trim($_POST['email_aluno'] ?? '');
    $matricula       = trim($_POST['matricula_aluno'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
} elseif ($tipo === 'professor') {
    $email           = trim($_POST['email_prof'] ?? '');
    $matricula       = trim($_POST['matricula_prof'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
} elseif ($tipo === 'administrador') {
    $email           = trim($_POST['email_prof'] ?? ''); // Reaproveitado, ou você pode criar um campo específico
    $matricula       = trim($_POST['matricula_admin'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
}

// ❌ Campos obrigatórios
if (!$email) {
    redir(false, 'O campo E-mail é obrigatório.');
}
if (!$matricula) {
    redir(false, 'O campo Matrícula é obrigatório.');
}
if (!$data_nascimento) {
    redir(false, 'O campo Data de Nascimento é obrigatório.');
}

// ✅ Validações de unicidade e inserção
try {
    // CPF duplicado
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE cpf = :cpf LIMIT 1");
    $stmt->execute([':cpf' => $cpf]);
    if ($stmt->fetch()) {
        redir(false, 'CPF já cadastrado.');
    }

    // Matrícula duplicada
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE matricula = :matricula LIMIT 1");
    $stmt->execute([':matricula' => $matricula]);
    if ($stmt->fetch()) {
        redir(false, 'Matrícula já cadastrada.');
    }

    // E-mail duplicado
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        redir(false, 'E-mail já cadastrado.');
    }

    // Hash seguro da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserção no banco
    $sql = "INSERT INTO usuarios 
            (tipo, nome, cpf, matricula, email, nome_pai, nome_mae, data_nascimento, senha_hash)
            VALUES 
            (:tipo, :nome, :cpf, :matricula, :email, :nome_pai, :nome_mae, :data_nascimento, :senha_hash)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tipo'            => $tipo,
        ':nome'            => $nome,
        ':cpf'             => $cpf,
        ':matricula'       => $matricula,
        ':email'           => $email,
        ':nome_pai'        => $nome_pai,
        ':nome_mae'        => $nome_mae,
        ':data_nascimento' => $data_nascimento,
        ':senha_hash'      => $senhaHash
    ]);

    // ✅ Redireciona para página de sucesso (sem parâmetros na URL)
    header('Location: cadastro_sucesso.php');
    exit;

} catch (Throwable $e) {
    redir(false, 'Erro ao cadastrar usuário.');
}