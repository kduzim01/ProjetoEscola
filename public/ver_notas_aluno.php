<?php
$PERFIS_PERMITIDOS = ['administrador', 'professor'];
require_once 'verifica_sessao.php';
require_once 'conexao.php';

$erro = $_SESSION['erro'] ?? null;
$msg  = $_SESSION['msg'] ?? null;
unset($_SESSION['erro'], $_SESSION['msg']);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['erro'] = "Aluno não encontrado.";
    header("Location: listar_alunos.php");
    exit;
}

$alunoId = intval($_GET['id']);

$sqlAluno = "SELECT id, nome, matricula FROM usuarios WHERE id = :id AND tipo = 'aluno' LIMIT 1";
$stmtAluno = $pdo->prepare($sqlAluno);
$stmtAluno->execute([':id' => $alunoId]);
$aluno = $stmtAluno->fetch(PDO::FETCH_ASSOC);

if (!$aluno) {
    $_SESSION['erro'] = "Aluno não encontrado.";
    header("Location: listar_alunos.php");
    exit;
}

// agora puxa nota_final e status
$sqlNotas = "
    SELECT nota_final, status
    FROM notas
    WHERE aluno_id = :aluno_id
    ORDER BY data_registro DESC
";

$stmtNotas = $pdo->prepare($sqlNotas);
$stmtNotas->execute([':aluno_id' => $alunoId]);
$notas = $stmtNotas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Notas do Aluno</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

  <div class="header">
      <div class="muted">🎓 Sistema Escolar</div>
      <div class="muted">
          Olá, <?= htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
      </div>
  </div>

  <div class="card" style="max-width: 900px; margin: 0 auto;">
      
      <h2 style="margin-top: 0;">
        Notas de <?= htmlspecialchars($aluno['nome']) ?> 
        (Matrícula: <?= htmlspecialchars($aluno['matricula']) ?>)
      </h2>

      <?php if ($erro): ?>
          <div class="alert error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <?php if ($msg): ?>
          <div class="alert ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <table class="table" style="width:100%; border-collapse:collapse; margin-top:20px;">
          <thead>
              <tr>
                  <th style="padding:8px;">Média Final</th>
                  <th style="padding:8px; text-align:center;">Situação</th>
              </tr>
          </thead>

          <tbody>
          <?php if (count($notas) === 0): ?>
              <tr>
                  <td colspan="2" style="text-align:center; padding:20px;">Nenhuma nota cadastrada.</td>
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
          <button class="btn light" type="button" onclick="window.location.href='listar_alunos.php'">
              Voltar
          </button>
      </div>

  </div>

  <div class="footer center">© <?= date('Y'); ?> Sistema Escolar</div>

</div>

</body>
</html>
