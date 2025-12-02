<?php
$PERFIS_PERMITIDOS = ['aluno']; 
require_once 'verifica_sessao.php';
require_once 'conexao.php';

$erro = $_SESSION['erro'] ?? null;
$msg  = $_SESSION['msg'] ?? null;
unset($_SESSION['erro'], $_SESSION['msg']);

$alunoId = $_SESSION['usuario_id'];

$sql = "
    SELECT nota_final, status
    FROM notas
    WHERE aluno_id = :aluno_id
    ORDER BY data_registro DESC
";

$stmtNotas = $pdo->prepare($sql);
$stmtNotas->execute([':aluno_id' => $alunoId]);
$notas = $stmtNotas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Minhas Notas</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
  <div class="header">
      <div class="muted">🎓 Sistema Escolar</div>
      <div class="muted">
          Olá, <?= htmlspecialchars($_SESSION['nome']) ?>
      </div>
  </div>

  <div class="card" style="max-width: 900px; margin: 0 auto;">
    
    <h2>Minhas Notas</h2>

    <?php if ($erro): ?>
        <div class="alert error"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if ($msg): ?>
        <div class="alert ok"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <table class="table" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr>
                <th style="padding:8px; text-align:center;">Média Final</th>
                <th style="padding:8px; text-align:center;">Situação</th>
            </tr>
        </thead>

        <tbody>
        <?php if (count($notas) === 0): ?>
            <tr>
                <td colspan="2" style="text-align:center; padding:20px;">Nenhuma média cadastrada.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($notas as $n): ?>
                <tr style="border-top:1px solid #ccc;">
                    <td style="padding:8px; text-align:center;">
                        <?= htmlspecialchars($n['nota_final']) ?>
                    </td>

                    <td style="padding:8px; text-align:center; 
                        color: <?= strtolower($n['status']) === 'aprovado' ? 'green' : 'red' ?>;">
                        <?= htmlspecialchars(ucfirst($n['status'])) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="bttns" style="margin-top:30px;">
        <button class="btn light" onclick="window.location.href='dashboard_aluno.php'">
            Voltar
        </button>
    </div>

  </div>

  <div class="footer center">© <?= date('Y'); ?> Sistema Escolar</div>
</div>

</body>
</html>
