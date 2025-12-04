<?php
$PERFIS_PERMITIDOS = ['professor']; // Somente professores acessam
require_once 'verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Dashboard Professor - Sistema Escolar</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <nav class="sidebar" role="navigation" aria-label="Menu do professor">
    <h2>Sistema Escolar</h2>
    <a href="dashboard_professor.php" aria-current="page">Início</a>
    <a href="minhas_turmas.php">Minhas Turmas</a>
    <a href="lancar_notas.php">Lançar Notas</a>
    <a href="frequencia_prof.php">Registrar Frequência</a>
    <form action="logout.php" method="post" style="margin-top:auto;">
      <button class="logout-btn" type="submit">Logout</button>
    </form>
  </nav>

  <main class="content" role="main">
    <h1>Bem-vindo, Prof. <?= htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></h1>

    <section class="cards">
      <article class="card" tabindex="0">
        <h3>Minhas Turmas</h3>
        <p>Gerencie suas turmas e acompanhe os alunos.</p>
        <a class="btn" href="minhas_turmas.php">Ver turmas</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Lançar Notas</h3>
        <p>Cadastre ou edite as notas dos alunos.</p>
        <a class="btn" href="lancar_notas.php">Lançar notas</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Registrar Frequência</h3>
        <p>Mantenha o controle de presença dos alunos.</p>
        <a class="btn" href="frequencia_prof.php">Registrar presença</a>
      </article>
    </section>
  </main>
</body>
</html>
