function aplicarMascaraTelefone(input) {
    let v = input.value.replace(/\D/g, '').slice(0, 11);
    input.value = v.replace(/^(\d{2})(\d{4,5})(\d{0,4}).*/, (_, ddd, p1, p2) =>
        `(${ddd}) ${p1}${p2 ? '-' + p2 : ''}`);
}

function obterNumerosTelefone(tel) {
    return tel.replace(/\D/g, '');
}

function validarEmail(email, max = 60) {
    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email) && email.length <= max;
}

function validarNome(nome, max = 50) {
    return nome.length >= 3 && nome.length <= max && /^[a-zA-ZÀ-ÿ\s]+$/.test(nome);
}

function validarSenha(senha, obrigatoria = true) {
    if (!senha && !obrigatoria) return true;
    return senha.length >= 8 && senha.length <= 50;
}

function validarTelefone(telefone) {
    let n = obterNumerosTelefone(telefone);
    return n.length >= 10 && n.length <= 11;
}

function validarCampo(campo, tipo) {
    let v = campo.value.trim(), msg = "";
    let l = v.length;

    switch (tipo) {
        case "nome": msg = !validarNome(v) ? "Nome inválido" : ""; break;
        case "email": msg = !validarEmail(v) ? "E-mail inválido" : ""; break;
        case "senha": msg = !validarSenha(v) ? "Senha inválida" : ""; break;
        case "nome_fornecedor": msg = l < 3 || l > 100 ? "Nome do fornecedor inválido" : ""; break;
        case "endereco": msg = l < 5 || l > 255 ? "Endereço inválido" : ""; break;
        case "telefone": msg = !validarTelefone(v) ? "Telefone inválido" : ""; break;
        case "contato": msg = l < 3 || l > 100 ? "Contato inválido" : ""; break;
    }

    campo.classList.remove('is-invalid', 'is-valid');
    let erro = campo.parentNode.querySelector('.erro-validacao');
    if (erro) erro.remove();

    if (msg) {
        let span = document.createElement('span');
        span.className = 'erro-validacao text-danger small';
        span.textContent = msg;
        campo.parentNode.appendChild(span);
        campo.classList.add('is-invalid');
    } else {
        campo.classList.add('is-valid');
    }
}

function validarUsuario(alt = false) {
    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();
    let senha = document.getElementById(alt ? "nova_senha" : "senha")?.value || "";
    let perfil = document.getElementById("id_perfil").value;

    if (!validarNome(nome)) return alert("Nome inválido"), document.getElementById("nome").focus(), false;
    if (!validarEmail(email)) return alert("E-mail inválido"), document.getElementById("email").focus(), false;
    if (!validarSenha(senha, !alt)) return alert("Senha inválida"), document.getElementById(alt ? "nova_senha" : "senha").focus(), false;
    if (!perfil || perfil == "0") return alert("Selecione um perfil válido"), document.getElementById("id_perfil").focus(), false;

    return true;
}

function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;

    if (!validarNome(nome)) return alert("Nome do funcionário inválido"), false;
    if (!validarTelefone(telefone)) return alert("Telefone inválido"), false;
    if (!validarEmail(email)) return alert("E-mail inválido"), false;

    return true;
}

function validarFornecedor(alt = false) {
    let nome = document.getElementById("nome_fornecedor").value.trim();
    let endereco = document.getElementById("endereco").value.trim();
    let telefone = document.getElementById("telefone").value.trim();
    let email = document.getElementById("email").value.trim();
    let contato = document.getElementById("contato").value.trim();

    if (nome.length < 3 || nome.length > 100)
        return alert("Nome do fornecedor inválido"), document.getElementById("nome_fornecedor").focus(), false;

    if (endereco.length < 5 || endereco.length > 255)
        return alert("Endereço inválido"), document.getElementById("endereco").focus(), false;

    if (!validarTelefone(telefone))
        return alert("Telefone inválido"), document.getElementById("telefone").focus(), false;

    if (!validarEmail(email, 100))
        return alert("E-mail inválido"), document.getElementById("email").focus(), false;

    if (contato.length < 3 || contato.length > 100)
        return alert("Contato inválido"), document.getElementById("contato").focus(), false;

    return true;
}
