<?php
require_once 'conexao.php';
session_start();

// Função para validar CPF com regex
function validarCPF($cpf) {
    return preg_match('/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/', $cpf) || preg_match('/^\d{11}$/', $cpf);
}

// Obtém dados via POST
$matricula = isset($_POST['matricula']) ? trim($_POST['matricula']) : '';
$senha     = $_POST['senha'] ?? '';

// Validação de campos obrigatórios
if ($matricula === '' || $senha === '') {
    $_SESSION['erro_login'] = 'Preencha todos os campos.';
    header('Location: ../index.php');
    exit;
}

// Tenta buscar por matrícula
$sql = "SELECT id, tipo, nome, senha_hash FROM usuarios WHERE matricula = :login LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':login', $matricula);
$stmt->execute();
$usuario = $stmt->fetch();

// Se não encontrou por matrícula e CPF for válido, tenta buscar por CPF (alunos)
if (!$usuario && validarCPF($matricula)) {
    $sql2 = "SELECT id, tipo, nome, senha_hash FROM usuarios WHERE cpf = :cpf AND tipo = 'aluno' LIMIT 1";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':cpf', $matricula);
    $stmt2->execute();
    $usuario = $stmt2->fetch();
}

// Verifica se usuário foi encontrado E se a senha está correta
if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
    $_SESSION['erro_login'] = 'Matrícula ou senha inválidos.';
    header('Location: ../index.php');
    exit;
}

// Login válido — prossegue

// Regenera ID de sessão
session_regenerate_id(true);

// Salva dados do usuário na sessão
$_SESSION['usuario_id'] = (int)$usuario['id'];
$_SESSION['perfil']     = $usuario['tipo'];
$_SESSION['nome']       = $usuario['nome'];
$_SESSION['matricula']  = $matricula;

// Cria token aleatório para autenticação
$token = bin2hex(random_bytes(32));

// Guarda token no banco com hash
$sqlToken = "INSERT INTO tokens_login (usuario_id, token, expiracao) VALUES (:usuario_id, :token, DATE_ADD(NOW(), INTERVAL 10 MINUTE))";
$stmtToken = $pdo->prepare($sqlToken);
$stmtToken->execute([
    ':usuario_id' => $usuario['id'],
    ':token' => hash('sha256', $token)
]);

// Define cookie seguro com o token
setcookie('auth_token', $token, time() + 600, '/', '', isset($_SERVER['HTTPS']), true);

// Redireciona com base no tipo de usuário
switch ($_SESSION['perfil']) {
    case 'administrador':
        header('Location: dashboard.php');
        break;
    case 'professor':
        header('Location: dashboard_professor.php');
        break;
    case 'aluno':
        header('Location: dashboard_aluno.php');
        break;
    default:
        session_destroy();
        header('Location: ../index.php?msg=Perfil%20inválido&type=erro');
        break;
}
exit;
?>
