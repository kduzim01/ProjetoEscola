<?php
session_start();

// Lê mensagens de erro e sucesso da sessão
$erro = $_SESSION['erro_login'] ?? null;
$msg  = $_SESSION['msg_login']  ?? null;

// Limpa as mensagens para não reaparecerem após o refresh
unset($_SESSION['erro_login'], $_SESSION['msg_login']);


// Lê cookie de matrícula para preencher o input
$matricula_cookie = $_COOKIE['matricula'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login - Sistema Escolar</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="header">
    <div class="muted">🎓 Sistema Escolar</div>
    <div class="muted">Autenticação segura</div>
  </div>

  <div class="card" style="max-width:520px;margin:0 auto;">
    <h2 style="margin-top:0">Entrar</h2>
    <p class="muted">Acesse com sua <strong>Matrícula</strong> e <strong>Senha</strong>.</p>

    <?php if ($erro): ?>
      <div class="alert error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
      <div class="alert ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="public/autentica.php" autocomplete="off" id="formLogin">
      <div class="field">
        <label for="matricula">Matrícula</label>
        <input
          type="text"
          id="matricula"
          name="matricula"
          autocomplete="username"
          value="<?= htmlspecialchars($matricula_cookie, ENT_QUOTES, 'UTF-8') ?>"
          maxlength="14" required
        >
        <div class="helper">Digite sua matrícula (para ALUNO, utilize o CPF se não possuir matrícula).</div>
        <div class="validation-msg" id="matricula-msg"></div> 
      </div>

      <div class="field password-container">
        <label for="senha">Senha</label>
        <div class="input-wrapper">
          <input type="password" id="senha" name="senha" required>
          <span class="toggle-password" data-target="senha" tabindex="0" role="button">👁️</span>
        </div>
        <div class="validation-msg" id="senha-msg"></div>
      </div>

      <div class="bttns">
        <button class="btn" type="submit">Entrar</button>
        <button class="btn light" type="reset">Limpar</button>
      </div>
    </form>

    <div class="forget">
      <a href="public/esqueci_senha.php">Esqueceu sua senha?</a>
    </div>
  </div>

  <div class="footer center">© <?= date('Y'); ?> Sistema Escolar</div>
</div>

<script src="assets/js/index_script.js"></script>

</body>
</html>
