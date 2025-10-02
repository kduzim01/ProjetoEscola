document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('search');
  const usuariosTable = document.getElementById('usuarios-table');
  const deleteButtons = usuariosTable.querySelectorAll('.btn.delete');

  // Função para filtrar usuários na tabela conforme o texto da busca
  searchInput.addEventListener('input', () => {
    const filtro = searchInput.value.toLowerCase();
    const linhas = usuariosTable.querySelectorAll('tbody tr');

    linhas.forEach(linha => {
      const nome = linha.querySelector('.nome').textContent.toLowerCase();
      const matricula = linha.querySelector('.matricula').textContent.toLowerCase();
      const perfil = linha.querySelector('.perfil').textContent.toLowerCase();

      if (nome.includes(filtro) || matricula.includes(filtro) || perfil.includes(filtro)) {
        linha.style.display = '';
      } else {
        linha.style.display = 'none';
      }
    });
  });

  // Adiciona confirmação para exclusão de usuário
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', e => {
      if (!confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.')) {
        e.preventDefault();
      }
    });
  });

  // Exemplo: botão para recarregar a lista (se houver)
  const reloadBtn = document.getElementById('reload-users');
  if (reloadBtn) {
    reloadBtn.addEventListener('click', () => {
      location.reload();
    });
  }
});
