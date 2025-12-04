<?php
require_once 'conexao.php';
$PERFIS_PERMITIDOS = ['administrador']; // apenas admin
require_once 'verifica_sessao.php';

// ⛔️ Função redir (usada só para ERROS ou mensagens)
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
$data_nascimento = null; // só será usado para alunos

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

// 📌 Campos adicionais opcionais (pai/mãe apenas para aluno)
$nome_pai = null;
$nome_mae = null;

// 🎓 Coleta de campos específicos por tipo
if ($tipo === 'aluno') {
    $email           = trim($_POST['email_aluno'] ?? '');
    $matricula       = trim($_POST['matricula_aluno'] ?? '');
    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $nome_pai        = trim($_POST['nome_pai'] ?? '') ?: null;
    $nome_mae        = trim($_POST['nome_mae'] ?? '') ?: null;

} elseif ($tipo === 'professor') {
    $email           = trim($_POST['email_prof'] ?? '');
    $matricula       = trim($_POST['matricula_prof'] ?? '');
    // Professores não têm data de nascimento no sistema

} elseif ($tipo === 'administrador') {
    $email           = trim($_POST['email_admin'] ?? '');
    $matricula       = trim($_POST['matricula_admin'] ?? '');
    // Administradores também não têm data de nascimento no sistema
}

// ❌ Validação obrigatória de campos comuns
if (!$email) {
    redir(false, 'O campo E-mail é obrigatório.');
}
if (!$matricula) {
    redir(false, 'O campo Matrícula é obrigatório.');
}
if ($tipo === 'aluno' && !$data_nascimento) {
    redir(false, 'O campo Data de Nascimento é obrigatório para alunos.');
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
        ':data_nascimento' => $tipo === 'aluno' ? $data_nascimento : null,
        ':senha_hash'      => $senhaHash
    ]);

    // ✅ Redireciona para página de sucesso
    header('Location: cadastro_sucesso.php');
    exit;

} catch (Throwable $e) {
    redir(false, 'Erro ao cadastrar usuário.');
}
