<?php
$PERFIS_PERMITIDOS = ['aluno', 'administrador', 'professor'];
require_once 'verifica_sessao.php';
require_once 'conexao.php';

// Consulta apenas alunos
$sql = "SELECT id, nome, matricula, email FROM usuarios WHERE tipo = 'aluno' ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$erro = $_SESSION['erro'] ?? null;
$msg  = $_SESSION['msg']  ?? null;
unset($_SESSION['erro'], $_SESSION['msg']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <div class="muted">🎓 Sistema Escolar</div>
        <div class="muted">
            Olá, <?= htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>

    <div class="card" style="max-width: 1000px; margin: 0 auto;">
        <h2 style="margin-top: 0;">Lista de Alunos Registrados</h2>

        <?php if ($erro): ?>
            <div class="alert error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="alert ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <!-- Tabela de alunos -->
        <table class="table" style="width:100%; border-collapse:collapse; margin-top:20px;">
            <thead>
                <tr>
                    <th style="padding:8px; text-align:left;">ID</th>
                    <th style="padding:8px; text-align:left;">Nome</th>
                    <th style="padding:8px; text-align:left;">Matrícula</th>
                    <th style="padding:8px; text-align:left;">Email</th>
                    <th style="padding:8px; text-align:center;">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (count($alunos) > 0): ?>
                <?php foreach ($alunos as $a): ?>
                    <tr style="border-top:1px solid #ccc;">
                        <td style="padding:8px;"><?= $a['id'] ?></td>
                        <td style="padding:8px;"><?= htmlspecialchars($a['nome']) ?></td>
                        <td style="padding:8px;"><?= htmlspecialchars($a['matricula']) ?></td>
                        <td style="padding:8px;"><?= htmlspecialchars($a['email']) ?></td>
                        <td style="padding:8px; text-align:center;">
                            
                            <!-- Botão para cadastrar nota deste aluno -->
                            <a class="btn small" 
                               href="cadastrar_nota.php?aluno_id=<?= $a['id'] ?>">
                               Cadastrar Nota
                            </a>

                            <!-- Ver notas do aluno -->
                            <a class="btn small light"
                               href="ver_notas_aluno.php?id=<?= $a['id'] ?>">
                               Ver Notas
                            </a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; padding:20px;">
                        Nenhum aluno encontrado.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <div class="bttns" style="margin-top: 25px;">
            <button class="btn light" type="button" onclick="window.location.href='dashboard.php'">
                Voltar
            </button>
        </div>
    </div>

    <div class="footer center">
        © <?= date('Y'); ?> Sistema Escolar
    </div>

</div>

</body>
</html>
