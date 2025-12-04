<?php
$PERFIS_PERMITIDOS = ['aluno']; // Somente alunos acessam
require_once 'verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Dashboard Aluno - Sistema Escolar</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <nav class="sidebar" role="navigation" aria-label="Menu do aluno">
    <h2>Sistema Escolar</h2>

    <!-- INÍCIO -->
    <a href="dashboard_aluno.php" aria-current="page">Início</a>
    <a href="notas_meu_rendimento.php">Minhas Notas</a>

    <a href="materiais.php">Materiais</a>
    <a href="frequencia.php">Frequência</a>

    <form action="logout.php" method="post" style="margin-top:auto;">
      <button class="logout-btn" type="submit">Logout</button>
    </form>
  </nav>

  <main class="content" role="main">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></h1>

    <section class="cards">

      <!-- CARD DE NOTAS CORRIGIDO -->
      <article class="card" tabindex="0">
        <h3>Minhas Notas</h3>
        <p>Veja suas notas e desempenho por disciplina.</p>
        <a class="btn" href="notas_meu_rendimento.php">Ver notas</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Materiais de Estudo</h3>
        <p>Acesse os conteúdos disponibilizados pelos professores.</p>
        <a class="btn" href="materiais.php">Ver materiais</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Frequência</h3>
        <p>Acompanhe sua presença nas aulas.</p>
        <a class="btn" href="frequencia.php">Ver frequência</a>
      </article>
