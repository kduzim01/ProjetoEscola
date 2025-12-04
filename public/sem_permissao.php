<?php
session_start();

// Se não estiver logado, manda direto pro login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../index.php');
    exit;
}

// Função para descobrir a dashboard correta com base no perfil
function getDashboardByPerfil($perfil) {
    switch ($perfil) {
        case 'administrador':
            return 'dashboard.php';
        case 'professor':
            return 'dashboard_professor.php';
        case 'aluno':
            return 'dashboard_aluno.php';
        default:
            return '../index.php'; // perfil inválido
    }
}

$dashboardURL = getDashboardByPerfil($_SESSION['perfil'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Sem permissão</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="card" style="max-width:640px;margin:40px auto;">
    <h2 style="margin-top:0">Acesso negado</h2>
    <p class="muted">Você não possui permissão para acessar esta página.</p>
    <a class="btn" href="<?= $dashboardURL ?>">Voltar ao Dashboard</a>
  </div>
</div>
</body>
</html>
