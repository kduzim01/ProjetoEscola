document.addEventListener('DOMContentLoaded', () => {
  const regex = {
    login: /^(\d{3}-\d{6}|\d{3}\.\d{3}\.\d{3}-\d{2})$/, // matrícula OU CPF completo
    cpf: /^\d{3}\.\d{3}\.\d{3}-\d{2}$/,                 // CPF completo
    senha: /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}$/, // Letras, números e caractere especial, min 12
  };

  const campos = [
    { id: 'matricula', tipo: 'login' },
    { id: 'senha', tipo: 'senha' },
  ];

  const btnEntrar = document.querySelector('button[type="submit"]');

  // Função para validar CPF incremental (xxx.xxx.xxx-xx)
  function validarCPFIncremental(valor) {
    const esperado = [3, 7, 11];
    for (let i = 0; i < valor.length; i++) {
      const char = valor[i];
      if (esperado.includes(i)) {
        if (i === 3 || i === 7) {
          if (char !== '.') return false;
        } else if (i === 11) {
          if (char !== '-') return false;
        }
      } else {
        if (!/\d/.test(char)) return false;
      }
    }
    return true;
  }

  // Função para validar matrícula incremental (000-000000)
  function validarMatriculaIncremental(valor) {
    if (valor.length > 10) return false;
    for (let i = 0; i < valor.length; i++) {
      const char = valor[i];
      if (i < 3) {
        if (!/\d/.test(char)) return false;
      } else if (i === 3) {
        if (char !== '-') return false;
      } else {
        if (!/\d/.test(char)) return false;
      }
    }
    return true;
  }

  // Função para validar login incremental (CPF ou matrícula)
  function validarLoginIncremental(valor) {
    if (validarCPFIncremental(valor)) return true;
    if (validarMatriculaIncremental(valor)) return true;
    return false;
  }

  // Validação dos campos
  function validarCampo(campo, tipo, msgEl) {
    const valor = campo.value.trim();

    if (!valor) {
      campo.classList.remove('valid', 'invalid');
      msgEl.textContent = '';
      atualizarBotao();
      return;
    }

    if (tipo === 'login') {
      if (!validarLoginIncremental(valor)) {
        campo.classList.add('invalid');
        campo.classList.remove('valid');
        msgEl.textContent = 'Digite a matrícula no formato 000-000000 ou CPF no formato 000.000.000-00';
        atualizarBotao();
        return;
      }

      // Se contém pontos e hífen, considera CPF e valida mais rigorosamente
      if (valor.includes('.') && valor.includes('-')) {
        if (!validarCPFIncremental(valor)) {
          campo.classList.add('invalid');
          campo.classList.remove('valid');
          msgEl.textContent = 'Digite o CPF no formato 000.000.000-00';
          atualizarBotao();
          return;
        }

        if (valor.length === 14) {
          if (!regex.cpf.test(valor)) {
            campo.classList.add('invalid');
            campo.classList.remove('valid');
            msgEl.textContent = 'Formato inválido. Exemplo: 000.000.000-90';
            atualizarBotao();
            return;
          }

          const cpfNumerico = valor.replace(/\D/g, '');
          const todosIguais = /^(\d)\1{10}$/.test(cpfNumerico);
          if (todosIguais) {
            campo.classList.add('invalid');
            campo.classList.remove('valid');
            msgEl.textContent = 'CPF inválido: todos os dígitos são iguais.';
            atualizarBotao();
            return;
          }

          campo.classList.add('valid');
          campo.classList.remove('invalid');
          msgEl.textContent = '';
          atualizarBotao();
          return;
        } else {
          campo.classList.remove('valid', 'invalid');
          msgEl.textContent = '';
          atualizarBotao();
          return;
        }
      }

      // Validação matrícula padrão
      if (regex.login.test(valor)) {
        campo.classList.add('valid');
        campo.classList.remove('invalid');
        msgEl.textContent = '';
      } else {
        campo.classList.remove('valid', 'invalid');
        msgEl.textContent = '';
      }

      atualizarBotao();
      return;
    }

    if (tipo === 'senha') {
      if (valor.length < 12) {
        campo.classList.remove('valid', 'invalid');
        msgEl.textContent = '';
        atualizarBotao();
        return;
      }

      if (regex.senha.test(valor)) {
        campo.classList.add('valid');
        campo.classList.remove('invalid');
        msgEl.textContent = '';
      } else {
        campo.classList.add('invalid');
        campo.classList.remove('valid');
        msgEl.textContent = 'Senha deve ter no mínimo 12 caracteres, incluindo letras, números e caractere especial.';
      }
      atualizarBotao();
      return;
    }
  }

  // Função que habilita/desabilita o botão de enviar e atualiza o tooltip
  function atualizarBotao() {
    // Verifica se todos os campos necessários têm a classe 'valid'
    const todosValidos = campos.every(({ id }) => {
      const campo = document.getElementById(id);
      return campo && campo.classList.contains('valid');
    });

    if (todosValidos) {
      btnEntrar.disabled = false;
      btnEntrar.title = '';
      btnEntrar.classList.remove('btn-disabled'); // só se você usar essa classe para estilo
    } else {
      btnEntrar.disabled = true;
      btnEntrar.title = 'Preencha corretamente os campos para habilitar o botão';
      btnEntrar.classList.add('btn-disabled'); // para efeito visual
    }
  }

  // Inicia validação dos campos e adiciona eventos
  campos.forEach(({ id, tipo }) => {
    const campo = document.getElementById(id);
    if (campo) {
      const msg = document.getElementById(`${id}-msg`);
      campo.addEventListener('input', () => validarCampo(campo, tipo, msg));
      campo.addEventListener('blur', () => validarCampo(campo, tipo, msg));
    }
  });

  // Preencher matrícula se cookie existir
  const form = document.getElementById('formLogin');
  const lembrarCheckbox = document.getElementById('lembrar');
  const matriculaInput = document.getElementById('matricula');

  const matriculaCookie = document.cookie.split('; ').find(row => row.startsWith('matricula='));
  if (matriculaCookie) {
    const valor = decodeURIComponent(matriculaCookie.split('=')[1]);
    matriculaInput.value = valor;
    if (lembrarCheckbox) lembrarCheckbox.checked = true;

    // Dispara validação inicial
    matriculaInput.dispatchEvent(new Event('input'));
  }

  // Criar ou remover cookie ao enviar formulário
  form.addEventListener('submit', () => {
    const matricula = matriculaInput.value.trim();
    if (lembrarCheckbox && lembrarCheckbox.checked && matricula) {
      document.cookie = `matricula=${encodeURIComponent(matricula)}; max-age=${60 * 60 * 24 * 30}; path=/`;
    } else {
      document.cookie = 'matricula=; max-age=0; path=/';
    }
  });

  // Olhinho toggle (👁️/🙈)
  document.querySelectorAll('.toggle-password').forEach(icon => {
    icon.addEventListener('click', () => {
      const targetId = icon.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (input) {
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        icon.textContent = isPassword ? '🙈' : '👁️';
      }
    });
  });

  // Inicializa estado do botão no carregamento
  atualizarBotao();
});
