<?php
$PERFIS_PERMITIDOS = ['administrador']; // Somente admin pode acessar este dashboard
require_once 'verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Dashboard Admin - Sistema Escolar</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <nav class="sidebar" role="navigation" aria-label="Menu principal">
    <h2>Sistema Escolar</h2>
    <a href="dashboard.php" aria-current="page">Dashboard</a>
    <a href="cadastro_usuarios.php">Gerenciar Usuários</a>
    <a href="relatorios.php">Relatórios</a>
    <a href="configuracoes.php">Configurações</a>
    <form action="logout.php" method="post" style="margin-top:auto;">
      <button class="logout-btn" type="submit" aria-label="Sair do sistema">Logout</button>
    </form>
  </nav>

  <main class="content" role="main">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome'], ENT_QUOTES, 'UTF-8'); ?></h1>

    <?php if (isset($_GET['msg'])): 
        // Sanitiza a mensagem para evitar XSS
        $msg = htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');
        // Define o tipo da mensagem; default "sucesso", mas pode receber 'erro' na URL
        $tipo = (isset($_GET['tipo']) && $_GET['tipo'] === 'erro') ? 'erro' : 'sucesso';
    ?>
        <div role="alert" class="mensagem <?= $tipo ?>">
            <?= $msg ?>
        </div>
    <?php endif; ?>

    <section aria-labelledby="usuarios-titulo" class="cards">
      <article class="card" tabindex="0">
        <h3 id="usuarios-titulo">Gerenciamento de Usuários</h3>
        <p>Cadastre alunos, professores e administradores.</p>
        <a class="btn" href="cadastro_usuarios.php">Abrir cadastro</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Relatórios</h3>
        <p>Acesse relatórios de desempenho e atividades.</p>
        <a class="btn" href="relatorios.php">Ver relatórios</a>
      </article>

      <article class="card" tabindex="0">
        <h3>Configurações do Sistema</h3>
        <p>Personalize preferências e parâmetros do sistema.</p>
        <a class="btn" href="configuracoes.php">Configurar</a>
      </article>

      <article class="card" tabindex="0">
      <h3>Gerenciar Alunos e Notas</h3>
      <p>Acesse a lista de alunos para adicionar, editar ou consultar notas.</p>
      <a class="btn" href="listar_alunos.php">Abrir Gerenciamento</a>
      </article>
    </section>
  </main>
  <script src="../assets/js/dashboard_admin_script.js"></script>
</body>
</html>
