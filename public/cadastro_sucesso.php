<?php
require_once 'verifica_sessao.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cadastro Realizado</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
  <div class="container centered">
    <div class="card sucesso">
      <h1 class="titulo-sucesso">Cadastro Realizado!</h1>
      <p class="mensagem-sucesso">
        O usuário foi cadastrado com sucesso no sistema.
      </p>
      <div class="botoes-sucesso">
        <a href="cadastro_usuarios.php" class="btn">Cadastrar Novo Usuário</a>
        <a href="dashboard.php" class="btn light">Voltar ao Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>