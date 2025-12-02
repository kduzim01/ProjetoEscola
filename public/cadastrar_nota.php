<?php
$PERFIS_PERMITIDOS = ['administrador', 'professor'];
require_once 'verifica_sessao.php';
require_once 'conexao.php';

$erro = $_SESSION['erro'] ?? null;
$msg  = $_SESSION['msg']  ?? null;
unset($_SESSION['erro'], $_SESSION['msg']);

$stmt = $pdo->prepare("SELECT id, nome, matricula FROM usuarios WHERE tipo = 'aluno' ORDER BY nome");
$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastrar Média Final</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">

  <div class="header">
    <div class="muted">🎓 Sistema Escolar</div>
    <div class="muted">Olá, <?= htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
  </div>

  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-top: 0;">Cadastrar Média Final</h2>

    <?php if ($erro): ?>
      <div class="alert error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
      <div class="alert ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="processa_cadastrar_nota.php" autocomplete="on">

      <div class="field">
        <label for="aluno_id">Aluno</label>
        <select name="aluno_id" id="aluno_id" required>
          <option value="">Selecione um aluno</option>
          <?php foreach ($alunos as $a): ?>
            <option value="<?= $a['id'] ?>">
              <?= htmlspecialchars($a['nome']) ?> — Matrícula: <?= htmlspecialchars($a['matricula']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label for="nota">Média Final</label>
        <input type="number" id="nota" name="nota" min="0" max="10" step="0.1" placeholder="0 a 10" 
               required oninput="atualizarSituacao()">
      </div>

      <div class="field">
        <label for="situacao">Situação</label>
        <input type="text" id="situacao" disabled placeholder="Aprovado/Reprovado">
        <input type="hidden" id="situacao_hidden" name="situacao">
      </div>

      <div class="bttns" style="margin-top: 30px;">
        <button class="btn" type="submit">Salvar Média</button>
        <button class="btn light" type="reset">Limpar</button>
        <button class="btn light" type="button" onclick="window.location.href='listar_alunos.php'">Voltar</button>
      </div>

    </form>
  </div>

  <div class="footer center">© <?= date('Y'); ?> Sistema Escolar</div>
</div>

<script>
function atualizarSituacao() {
    let nota = parseFloat(document.getElementById("nota").value);
    let campoSituacao = document.getElementById("situacao");
    let campoHidden = document.getElementById("situacao_hidden");

    if (isNaN(nota)) {
        campoSituacao.value = "";
        campoHidden.value = "";
        return;
    }

    let status = (nota >= 6) ? "Aprovado" : "Reprovado";

    campoSituacao.value = status;
    campoHidden.value = status;
}
</script>

</body>
</html>
