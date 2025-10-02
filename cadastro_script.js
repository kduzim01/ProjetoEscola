const regex = {
  nome: /^[A-Za-zÀ-ÿ\s]{2,}$/,
  cpf: /^\d{3}\.\d{3}\.\d{3}-\d{2}$/,
  email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
  matricula: /^\d{3}-\d{6}$/,
  senha: /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{12,}$/,
};

const campos = [
  { id: 'nome', tipo: 'nome' },
  { id: 'nome_pai', tipo: 'nome' },
  { id: 'nome_mae', tipo: 'nome' },
  { id: 'cpf', tipo: 'cpf' },
  { id: 'email_aluno', tipo: 'email' },
  { id: 'email_prof', tipo: 'email' },
  { id: 'matricula_aluno', tipo: 'matricula' },
  { id: 'matricula_prof', tipo: 'matricula' },
  { id: 'matricula_admin', tipo: 'matricula' },
  { id: 'senha', tipo: 'senha' },
  { id: 'senha2', tipo: 'senha2' },
];

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

function validarEmailIncremental(valor) {
  if (!valor.includes('@')) return true;
  const padraoParcial = /^[^\s@]+@[^\s@]+\.[^\s@]{1,}$/;
  if (/\s/.test(valor)) return false;
  if (valor.includes('.') && !padraoParcial.test(valor)) return false;
  return true;
}

function validarCampo(campo, tipo) {
  const valor = campo.value.trim();
  const msgEl = campo.nextElementSibling;

  if (!valor) {
    campo.classList.remove('valid', 'invalid');
    if (msgEl?.classList?.contains('validation-msg')) msgEl.textContent = '';
    return;
  }

  if (tipo === 'senha') {
    if (valor.length < 12) {
      campo.classList.remove('valid', 'invalid');
      msgEl.textContent = '';
      return;
    }
    if (regex.senha.test(valor)) {
      campo.classList.add('valid');
      campo.classList.remove('invalid');
      msgEl.textContent = '';
    } else {
      campo.classList.add('invalid');
      campo.classList.remove('valid');
      msgEl.textContent =
        'Senha deve ter no mínimo 12 caracteres, incluindo letras, números e caractere especial.';
    }
    return;
  }

if (tipo === 'senha2') {
  const senha = document.getElementById('senha').value;
  const senha2 = campo.value;

  // Se a senha principal ainda não for válida, não validar confirmação
  if (senha.length < 12 || !regex.senha.test(senha)) {
    campo.classList.remove('valid', 'invalid');
    msgEl.textContent = '';
    return;
  }

  // Se confirmação estiver vazia, não mostrar erro ainda
  if (senha2.length === 0) {
    campo.classList.remove('valid', 'invalid');
    msgEl.textContent = '';
    return;
  }

  // Se senha2 for maior que senha ou não começar igual, é divergente
  if (senha2.length > senha.length || !senha.startsWith(senha2)) {
    campo.classList.add('invalid');
    campo.classList.remove('valid');
    msgEl.textContent = 'As senhas estão divergindo.';
    return;
  }

  // Se senha2 for igual à senha
  if (senha2 === senha) {
    campo.classList.add('valid');
    campo.classList.remove('invalid');
    msgEl.textContent = '';
    return;
  }

  // Ainda está digitando, mas até agora tudo certo
  campo.classList.remove('valid', 'invalid');
  msgEl.textContent = '';
  return;
}

  if (tipo === 'cpf') {
    if (!validarCPFIncremental(valor)) {
      campo.classList.add('invalid');
      campo.classList.remove('valid');
      msgEl.textContent = 'Digite o CPF no formato 000.000.000-00';
      return;
    }

    if (valor.length === 14) {
      if (!regex.cpf.test(valor)) {
        campo.classList.add('invalid');
        campo.classList.remove('valid');
        msgEl.textContent = 'Formato inválido. Exemplo: 000.000.000-90';
        return;
      }

      const cpfNumerico = valor.replace(/\D/g, '');
      const todosIguais = /^(\d)\1{10}$/.test(cpfNumerico);
      if (todosIguais) {
        campo.classList.add('invalid');
        campo.classList.remove('valid');
        msgEl.textContent = 'CPF inválido: todos os dígitos são iguais.';
        return;
      }

      campo.classList.add('valid');
      campo.classList.remove('invalid');
      msgEl.textContent = '';
    } else {
      campo.classList.remove('invalid', 'valid');
      msgEl.textContent = '';
    }
    return;
  }

  if (tipo === 'matricula') {
    if (!validarMatriculaIncremental(valor)) {
      campo.classList.add('invalid');
      campo.classList.remove('valid');
      msgEl.textContent = 'Digite a matrícula no formato 000-000000';
      return;
    }
    if (valor.length === 10) {
      if (regex.matricula.test(valor)) {
        campo.classList.add('valid');
        campo.classList.remove('invalid');
        msgEl.textContent = '';
      } else {
        campo.classList.add('invalid');
        campo.classList.remove('valid');
        msgEl.textContent = 'Formato inválido. Exemplo: 000-000000';
      }
    } else {
      campo.classList.remove('invalid', 'valid');
      msgEl.textContent = '';
    }
    return;
  }

  if (tipo === 'email') {
    if (!validarEmailIncremental(valor)) {
      campo.classList.add('invalid');
      campo.classList.remove('valid');
      msgEl.textContent = 'Formato inválido. Exemplo: usuario@exemplo.com';
      return;
    }
    if (regex.email.test(valor)) {
      campo.classList.add('valid');
      campo.classList.remove('invalid');
      msgEl.textContent = '';
    } else {
      campo.classList.remove('valid', 'invalid');
      msgEl.textContent = '';
    }
    return;
  }

  if (tipo === 'nome' && valor.length < 2) {
    campo.classList.remove('valid', 'invalid');
    msgEl.textContent = '';
    return;
  }

  if (regex[tipo] && regex[tipo].test(valor)) {
    campo.classList.add('valid');
    campo.classList.remove('invalid');
    msgEl.textContent = '';
  } else {
    campo.classList.add('invalid');
    campo.classList.remove('valid');
    switch (tipo) {
      case 'nome':
        msgEl.textContent = 'Formato inválido. Exemplo: Maria do Santos';
        break;
      case 'senha':
        msgEl.textContent = 'Senha deve conter letras, números e caractere especial.';
        break;
      default:
        msgEl.textContent = 'Formato inválido.';
    }
  }
}

document.addEventListener('DOMContentLoaded', function () {
  campos.forEach(({ id, tipo }) => {
    const campo = document.getElementById(id);
    if (campo) {
      const msg = document.createElement('div');
      msg.className = 'validation-msg';
      campo.insertAdjacentElement('afterend', msg);
      campo.addEventListener('input', () => validarCampo(campo, tipo));
      campo.addEventListener('blur', () => validarCampo(campo, tipo));
    }
  });

  const dataNasc = document.getElementById('data_nascimento');
  if (dataNasc) {
    const msg = document.createElement('div');
    msg.className = 'validation-msg';
    dataNasc.insertAdjacentElement('afterend', msg);
    dataNasc.addEventListener('input', () => {
      const valor = dataNasc.value;
      const hoje = new Date().toISOString().split('T')[0];
      if (valor && valor <= hoje) {
        dataNasc.classList.add('valid');
        dataNasc.classList.remove('invalid');
        msg.textContent = '';
      } else {
        dataNasc.classList.add('invalid');
        dataNasc.classList.remove('valid');
        msg.textContent = 'Data inválida.';
      }
    });
  }

  const tipo = document.getElementById('tipo');
  const camposAluno = document.getElementById('camposAluno');
  const camposProfessor = document.getElementById('camposProfessor');
  const camposAdmin = document.getElementById('camposAdmin');
  const camposComuns = document.getElementById('camposComuns');
  const camposSenha = document.getElementById('camposSenha');

  function toggleCampos() {
    if (!tipo.value) {
      if (camposComuns) camposComuns.style.display = 'none';
      if (camposAluno) camposAluno.style.display = 'none';
      if (camposProfessor) camposProfessor.style.display = 'none';
      if (camposAdmin) camposAdmin.style.display = 'none';
      if (camposSenha) camposSenha.style.display = 'none';
      return;
    }

    if (camposComuns) camposComuns.style.display = 'block';
    if (camposSenha) camposSenha.style.display = 'block';

    if (camposAluno) camposAluno.style.display = 'none';
    if (camposProfessor) camposProfessor.style.display = 'none';
    if (camposAdmin) camposAdmin.style.display = 'none';

    if (tipo.value === 'aluno' && camposAluno) camposAluno.style.display = 'block';
    else if (tipo.value === 'professor' && camposProfessor) camposProfessor.style.display = 'block';
    else if (tipo.value === 'administrador' && camposAdmin) camposAdmin.style.display = 'block';
  }

  if (tipo) {
    tipo.addEventListener('change', toggleCampos);
    toggleCampos(); // executa no carregamento
  }

// Campos que terão sugestões via cookie
const camposSugestivos = ['nome', 'cpf', 'email_aluno', 'email_prof'];

function salvarCookie(campo, valor) {
  if (valor && !campo.includes('senha')) {
    const chave = `${campo}_${Date.now()}`;
    document.cookie = `${chave}=${encodeURIComponent(valor)}; path=/`;
  }
}

function recuperarSugestoes(campo) {
  const cookies = document.cookie.split('; ');
  const valores = new Set();

  cookies.forEach(c => {
    const [chave, valor] = c.split('=');
    if (chave.startsWith(campo)) {
      valores.add(decodeURIComponent(valor));
    }
  });

  return Array.from(valores);
}

function preencherDatalist(campo) {
  const datalistId = `sugestoes-${campo}`;
  let datalist = document.getElementById(datalistId);

  if (!datalist) {
    datalist = document.createElement('datalist');
    datalist.id = datalistId;
    document.body.appendChild(datalist);
    document.getElementById(campo).setAttribute('list', datalistId);
  }

  datalist.innerHTML = '';
  const sugestoes = recuperarSugestoes(campo);
  sugestoes.forEach(s => {
    const option = document.createElement('option');
    option.value = s;
    datalist.appendChild(option);
  });
}

camposSugestivos.forEach(campoId => {
  const campo = document.getElementById(campoId);
  if (campo) {
    campo.addEventListener('blur', () => salvarCookie(campoId, campo.value));
    campo.addEventListener('focus', () => preencherDatalist(campoId));
  }
});

});

// Olhinho toggle (👁️/🙈)
['olhinho-senha', 'olhinho-senha2'].forEach(id => {
  const icon = document.getElementById(id);
  if (icon) {
    icon.addEventListener('click', () => {
      const targetId = icon.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (input) {
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        icon.textContent = isPassword ? '🙈' : '👁️';
      }
    });
  }
});