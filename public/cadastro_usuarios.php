<?php
$PERFIS_PERMITIDOS = ['administrador'];
require_once 'verifica_sessao.php';
$erro = $_SESSION['erro'] ?? null;
$msg  = $_SESSION['msg']  ?? null;
unset($_SESSION['erro'], $_SESSION['msg']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Cadastro de Usuários</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="header">
    <div class="muted">🎓 Sistema Escolar</div>
    <div class="muted">Olá, <?= htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?> (admin)</div>
  </div>

  <div class="card" style="max-width: 800px; margin: 0 auto;">
    <h2 style="margin-top: 0;">Cadastrar Novo Usuário</h2>

    <?php if ($erro): ?>
      <div class="alert error"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if ($msg): ?>
      <div class="alert ok"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" action="processa_cadastro.php" autocomplete="on" id="formCadastro">
      <div class="field">
        <label for="tipo">Tipo de usuário</label>
        <select name="tipo" id="tipo" autocomplete="off">
          <option value="">Selecione</option>
          <option value="aluno">Aluno</option>
          <option value="professor">Professor</option>
          <option value="administrador">Administrador</option>
        </select>
      </div>

      <div id="camposComuns">
        <div class="field">
          <label for="nome">Nome</label>
          <input type="text" id="nome" name="nome" placeholder="Ex.: Leucimar Carvalho" autocomplete="name" list="sugestoes-nome">
          <datalist id="sugestoes-nome"></datalist>
        </div>
        <div class="field">
          <label for="cpf">CPF</label>
          <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" autocomplete="off" list="sugestoes-cpf">
          <datalist id="sugestoes-cpf"></datalist>
        </div>
      </div>

      <div id="camposAluno" style="display: none;">
        <div class="field">
          <label for="matricula_aluno">Matrícula</label>
          <input type="text" id="matricula_aluno" name="matricula_aluno" placeholder="000-000000" autocomplete="off">
        </div>
        <div class="field">
          <label for="email_aluno">E-mail</label>
          <input type="email" id="email_aluno" name="email_aluno" placeholder="email@example.com" autocomplete="email" list="sugestoes-email_aluno">
          <datalist id="sugestoes-email_aluno"></datalist>
        </div>
        <div class="field">
          <label for="data_nascimento">Data de Nascimento</label>
          <input type="date" id="data_nascimento" name="data_nascimento" autocomplete="bday">
        </div>
        <div class="field">
          <label for="nome_pai">Nome do Pai</label>
          <input type="text" id="nome_pai" name="nome_pai" placeholder="Ex.: João da Silva" autocomplete="off">
        </div>
        <div class="field">
          <label for="nome_mae">Nome da Mãe</label>
          <input type="text" id="nome_mae" name="nome_mae" placeholder="Ex.: Maria de Lourdes" autocomplete="off">
        </div>
      </div>

      <div id="camposProfessor" style="display: none;">
        <div class="field">
          <label for="matricula_prof">Matrícula</label>
          <input type="text" id="matricula_prof" name="matricula_prof" placeholder="000-000000" autocomplete="off">
        </div>
        <div class="field">
          <label for="email_prof">E-mail</label>
          <input type="email" id="email_prof" name="email_prof" placeholder="email@example.com" autocomplete="email" list="sugestoes-email_prof">
          <datalist id="sugestoes-email_prof"></datalist>
        </div>
      </div>

      <div id="camposAdmin" style="display: none;">
        <div class="field">
          <label for="matricula_admin">Matrícula</label>
          <input type="text" id="matricula_admin" name="matricula_admin" placeholder="000-000000" autocomplete="off">
        </div>
      </div>

      <div id="camposSenha" style="display: none;">
        <div class="grid two">
          <div class="field password-container">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" autocomplete="new-password">
            <span id="olhinho-senha" data-target="senha">👁️</span>
          </div>
          <div class="field password-container">
            <label for="senha2">Confirmar Senha</label>
            <input type="password" id="senha2" name="senha2" autocomplete="new-password">
            <span id="olhinho-senha2" data-target="senha2">👁️</span>
          </div>
        </div>
      </div>

      <div class="bttns" style="margin-top: 30px;">
        <button class="btn" type="submit">Cadastrar</button>
        <button class="btn light" type="reset">Limpar</button>
        <button class="btn light" type="button" onclick="window.location.href='dashboard.php'">Voltar</button>
      </div>
    </form>
  </div>

  <div class="footer center">© <?= date('Y'); ?> Sistema Escolar</div>
</div>

<script src="../assets/js/cadastro_script.js"></script>
</body>
</html>
