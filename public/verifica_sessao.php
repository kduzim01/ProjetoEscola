<?php
require_once 'conexao.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Tempo máximo de inatividade em segundos (10 minutos)
$timeout = 600;

// Função para limpar token expirado ou inválido do banco
function limparToken($pdo, $tokenHash) {
    $sqlDel = "DELETE FROM tokens_login WHERE token = :token";
    $stmtDel = $pdo->prepare($sqlDel);
    $stmtDel->execute([':token' => $tokenHash]);
}

// 1) Se sessão não existe, tentar autenticar via cookie
if (!isset($_SESSION['usuario_id']) && isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];
    $tokenHash = hash('sha256', $token);

    // Busca token válido no banco (não expirado)
    $sql = "SELECT usuario_id FROM tokens_login WHERE token = :token AND expiracao > NOW() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token' => $tokenHash]);
    $row = $stmt->fetch();

    if ($row) {
        // Token válido: criar sessão automaticamente
        $sqlUser = "SELECT id, tipo, nome, matricula FROM usuarios WHERE id = :id LIMIT 1";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([':id' => $row['usuario_id']]);
        $usuario = $stmtUser->fetch();

        if ($usuario) {
            // Regenera a sessão e popula dados
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$usuario['id'];
            $_SESSION['perfil']     = $usuario['tipo'];
            $_SESSION['nome']       = $usuario['nome'];
            $_SESSION['matricula']  = $usuario['matricula'] ?? '';

            // 🔥 Correção importante:
            // Compatibiliza com páginas que usam id / tipo
            $_SESSION['id']   = (int)$usuario['id'];
            $_SESSION['tipo'] = $usuario['tipo'];

            // Atualiza o timestamp da última atividade
            $_SESSION['ultimo_acesso'] = time();

            // Renova o cookie (mais 10 minutos)
            setcookie('auth_token', $token, time() + $timeout, '/', '', isset($_SERVER['HTTPS']), true);

            // Renova validade do token no banco (mais 10 minutos)
            $sqlUpdate = "UPDATE tokens_login SET expiracao = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE token = :token";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([':token' => $tokenHash]);
        } else {
            // Usuário não encontrado, limpar token
            limparToken($pdo, $tokenHash);
            setcookie('auth_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
            header('Location: ../index.php');
            exit;
        }
    } else {
        // Token inválido ou expirado, limpar cookie e redirecionar
        setcookie('auth_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        header('Location: ../index.php');
        exit;
    }
}

// 2) Se ainda não tiver sessão, redirecionar para login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// 3) Verifica timeout de inatividade
if (isset($_SESSION['ultimo_acesso'])) {
    $tempo_inativo = time() - $_SESSION['ultimo_acesso'];
    if ($tempo_inativo > $timeout) {
        // Tempo de inatividade excedido: destruir sessão e cookie, redirecionar
        session_unset();
        session_destroy();
        setcookie('auth_token', '', time() - 3600, '/', '', isset($_SERVER['HTTPS']), true);
        header('Location: ../index.php?msg=' . urlencode('Sua sessão expirou por inatividade.'));
        exit;
    }
}
// Atualiza o timestamp da última atividade
$_SESSION['ultimo_acesso'] = time();

// 4) Verifica permissão por perfil, se a página definir $PERFIS_PERMITIDOS antes do include
if (isset($PERFIS_PERMITIDOS) && is_array($PERFIS_PERMITIDOS)) {

    $perfil = $_SESSION['perfil'] ?? $_SESSION['tipo'] ?? null;

    if (!in_array($perfil, $PERFIS_PERMITIDOS, true)) {
        header('Location: sem_permissao.php');
        exit;
    }
}
?>
