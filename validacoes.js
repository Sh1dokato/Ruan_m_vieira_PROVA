// Função para aplicar máscara de telefone
function aplicarMascaraTelefone(input) {
    let valor = input.value.replace(/\D/g, ''); // Remove tudo que não é dígito
    
    // Limita a 11 dígitos (padrão brasileiro)
    if (valor.length > 11) {
        valor = valor.substring(0, 11);
    }
    
    if (valor.length <= 11) {
        if (valor.length <= 2) {
            valor = valor;
        } else if (valor.length <= 6) {
            valor = valor.replace(/(\d{2})(\d)/, '($1) $2');
        } else if (valor.length <= 10) {
            valor = valor.replace(/(\d{2})(\d{4})(\d)/, '($1) $2-$3');
        } else {
            valor = valor.replace(/(\d{2})(\d{5})(\d)/, '($1) $2-$3');
        }
    }
    
    input.value = valor;
}

// Função para permitir apenas números no campo de telefone
function permitirApenasNumerosTelefone(input) {
    // Remove caracteres não numéricos
    let valor = input.value.replace(/\D/g, '');
    
    // Limita a 11 dígitos (padrão brasileiro)
    if (valor.length > 11) {
        valor = valor.substring(0, 11);
    }
    
    // Aplica a máscara
    if (valor.length <= 11) {
        if (valor.length <= 2) {
            input.value = valor;
        } else if (valor.length <= 6) {
            input.value = valor.replace(/(\d{2})(\d)/, '($1) $2');
        } else if (valor.length <= 10) {
            input.value = valor.replace(/(\d{2})(\d{4})(\d)/, '($1) $2-$3');
        } else {
            input.value = valor.replace(/(\d{2})(\d{5})(\d)/, '($1) $2-$3');
        }
    }
}

// Função para obter apenas os números do telefone (para validação)
function obterNumerosTelefone(telefone) {
    return telefone.replace(/\D/g, '');
}

function validarFuncionario() {
    let nome = document.getElementById("nome_funcionario").value;
    let telefone = document.getElementById("telefone").value;
    let email = document.getElementById("email").value;

    if (nome.length < 3) {
        alert("O nome do funcionário deve ter pelo menos 3 caracteres.");
        return false;
    }

    let numerosTelefone = obterNumerosTelefone(telefone);
    if (numerosTelefone.length < 10 || numerosTelefone.length > 11) {
        alert("Digite um telefone válido (DDD + número, total de 10 ou 11 dígitos).");
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        return false;
    }

    return true;
}

function validarUsuario() {
    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();
    let senha = document.getElementById("senha").value;
    let id_perfil = document.getElementById("id_perfil").value;


    if (nome.length < 3) {
        alert("O nome deve ter pelo menos 3 caracteres.");
        document.getElementById("nome").focus();
        return false;
    }

    if (nome.length > 50) {
        alert("O nome deve ter no máximo 50 caracteres.");
        document.getElementById("nome").focus();
        return false;
    }

    let regexNome = /^[a-zA-ZÀ-ÿ\s]+$/;
    if (!regexNome.test(nome)) {
        alert("O nome não pode conter números ou caracteres especiais.");
        document.getElementById("nome").focus();
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        document.getElementById("email").focus();
        return false;
    }

    if (email.length > 60) {
        alert("O e-mail deve ter no máximo 60 caracteres.");
        document.getElementById("email").focus();
        return false;
    }

    if (senha.length < 8) {
        alert("A senha deve ter pelo menos 8 caracteres.");
        document.getElementById("senha").focus();
        return false;
    }

    if (senha.length > 50) {
        alert("A senha deve ter no máximo 50 caracteres.");
        document.getElementById("senha").focus();
        return false;
    }

    if (id_perfil === "" || id_perfil === "0") {
        alert("Selecione um perfil válido.");
        document.getElementById("id_perfil").focus();
        return false;
    }

    return true;
}

function validarAlteracaoUsuario() {
    let nome = document.getElementById("nome").value.trim();
    let email = document.getElementById("email").value.trim();
    let id_perfil = document.getElementById("id_perfil").value;
    let nova_senha = document.getElementById("nova_senha");


    if (nome.length < 3) {
        alert("O nome deve ter pelo menos 3 caracteres.");
        document.getElementById("nome").focus();
        return false;
    }

    if (nome.length > 50) {
        alert("O nome deve ter no máximo 50 caracteres.");
        document.getElementById("nome").focus();
        return false;
    }


    let regexNome = /^[a-zA-ZÀ-ÿ\s]+$/;
    if (!regexNome.test(nome)) {
        alert("O nome não pode conter números ou caracteres especiais.");
        document.getElementById("nome").focus();
        return false;
    }


    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        document.getElementById("email").focus();
        return false;
    }

    if (email.length > 60) {
        alert("O e-mail deve ter no máximo 60 caracteres.");
        document.getElementById("email").focus();
        return false;
    }


    if (id_perfil === "" || id_perfil === "0") {
        alert("Selecione um perfil válido.");
        document.getElementById("id_perfil").focus();
        return false;
    }


    if (nova_senha && nova_senha.value !== "") {
        if (nova_senha.value.length < 8) {
            alert("A nova senha deve ter pelo menos 8 caracteres.");
            nova_senha.focus();
            return false;
        }

        if (nova_senha.value.length > 50) {
            alert("A nova senha deve ter no máximo 50 caracteres.");
            nova_senha.focus();
            return false;
        }
    }

    return true;
}

function validarCampo(campo, tipo) {
    let valor = campo.value.trim();
    let mensagem = "";

    switch(tipo) {
        case "nome":
            if (valor.length < 3) {
                mensagem = "Nome deve ter pelo menos 3 caracteres";
            } else if (valor.length > 50) {
                mensagem = "Nome deve ter no máximo 50 caracteres";
            } else {
                let regexNome = /^[a-zA-ZÀ-ÿ\s]+$/;
                if (!regexNome.test(valor)) {
                    mensagem = "Nome não pode conter números ou caracteres especiais";
                }
            }
            break;
        
        case "email":
            let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!regexEmail.test(valor)) {
                mensagem = "Digite um e-mail válido";
            } else if (valor.length > 60) {
                mensagem = "E-mail deve ter no máximo 60 caracteres";
            }
            break;
        
        case "senha":
            if (valor.length < 8) {
                mensagem = "Senha deve ter pelo menos 8 caracteres";
            } else if (valor.length > 50) {
                mensagem = "Senha deve ter no máximo 50 caracteres";
            }
            break;
        
        case "nome_fornecedor":
            if (valor.length < 3) {
                mensagem = "Nome do fornecedor deve ter pelo menos 3 caracteres";
            } else if (valor.length > 100) {
                mensagem = "Nome do fornecedor deve ter no máximo 100 caracteres";
            }
            break;
        
        case "endereco":
            if (valor.length < 5) {
                mensagem = "Endereço deve ter pelo menos 5 caracteres";
            } else if (valor.length > 255) {
                mensagem = "Endereço deve ter no máximo 255 caracteres";
            }
            break;
        
        case "telefone":
            let numerosTelefone = obterNumerosTelefone(valor);
            if (numerosTelefone.length < 10) {
                mensagem = "Telefone deve ter pelo menos 10 dígitos (DDD + número)";
            } else if (numerosTelefone.length > 11) {
                mensagem = "Telefone deve ter no máximo 11 dígitos (DDD + 9 dígitos)";
            }
            break;
        
        case "contato":
            if (valor.length < 3) {
                mensagem = "Contato deve ter pelo menos 3 caracteres";
            } else if (valor.length > 100) {
                mensagem = "Contato deve ter no máximo 100 caracteres";
            }
            break;
    }


    let erroAnterior = campo.parentNode.querySelector('.erro-validacao');
    if (erroAnterior) {
        erroAnterior.remove();
    }

   
    if (mensagem) {
        let spanErro = document.createElement('span');
        spanErro.className = 'erro-validacao text-danger small';
        spanErro.textContent = mensagem;
        campo.parentNode.appendChild(spanErro);
        campo.classList.add('is-invalid');
    } else {
        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
    }
}

function validarFornecedor() {
    let nome_fornecedor = document.getElementById("nome_fornecedor").value.trim();
    let endereco = document.getElementById("endereco").value.trim();
    let telefone = document.getElementById("telefone").value.trim();
    let email = document.getElementById("email").value.trim();
    let contato = document.getElementById("contato").value.trim();

    if (nome_fornecedor.length < 3) {
        alert("O nome do fornecedor deve ter pelo menos 3 caracteres.");
        document.getElementById("nome_fornecedor").focus();
        return false;
    }

    if (nome_fornecedor.length > 100) {
        alert("O nome do fornecedor deve ter no máximo 100 caracteres.");
        document.getElementById("nome_fornecedor").focus();
        return false;
    }

    if (endereco.length < 5) {
        alert("O endereço deve ter pelo menos 5 caracteres.");
        document.getElementById("endereco").focus();
        return false;
    }

    if (endereco.length > 255) {
        alert("O endereço deve ter no máximo 255 caracteres.");
        document.getElementById("endereco").focus();
        return false;
    }

    let numerosTelefone = obterNumerosTelefone(telefone);
    if (numerosTelefone.length < 10) {
        alert("O telefone deve ter pelo menos 10 dígitos (DDD + número).");
        document.getElementById("telefone").focus();
        return false;
    }

    if (numerosTelefone.length > 11) {
        alert("O telefone deve ter no máximo 11 dígitos (DDD + 9 dígitos).");
        document.getElementById("telefone").focus();
        return false;
    }

    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        document.getElementById("email").focus();
        return false;
    }

    if (email.length > 100) {
        alert("O e-mail deve ter no máximo 100 caracteres.");
        document.getElementById("email").focus();
        return false;
    }

   
    if (contato.length < 3) {
        alert("O contato deve ter pelo menos 3 caracteres.");
        document.getElementById("contato").focus();
        return false;
    }

    if (contato.length > 100) {
        alert("O contato deve ter no máximo 100 caracteres.");
        document.getElementById("contato").focus();
        return false;
    }

    return true;
}

function validarAlteracaoFornecedor() {
    let nome_fornecedor = document.getElementById("nome_fornecedor").value.trim();
    let endereco = document.getElementById("endereco").value.trim();
    let telefone = document.getElementById("telefone").value.trim();
    let email = document.getElementById("email").value.trim();
    let contato = document.getElementById("contato").value.trim();

    
    if (nome_fornecedor.length < 3) {
        alert("O nome do fornecedor deve ter pelo menos 3 caracteres.");
        document.getElementById("nome_fornecedor").focus();
        return false;
    }

    if (nome_fornecedor.length > 100) {
        alert("O nome do fornecedor deve ter no máximo 100 caracteres.");
        document.getElementById("nome_fornecedor").focus();
        return false;
    }


    if (endereco.length < 5) {
        alert("O endereço deve ter pelo menos 5 caracteres.");
        document.getElementById("endereco").focus();
        return false;
    }

    if (endereco.length > 255) {
        alert("O endereço deve ter no máximo 255 caracteres.");
        document.getElementById("endereco").focus();
        return false;
    }


    let numerosTelefone = obterNumerosTelefone(telefone);
    if (numerosTelefone.length < 10) {
        alert("O telefone deve ter pelo menos 10 dígitos (DDD + número).");
        document.getElementById("telefone").focus();
        return false;
    }

    if (numerosTelefone.length > 11) {
        alert("O telefone deve ter no máximo 11 dígitos (DDD + 9 dígitos).");
        document.getElementById("telefone").focus();
        return false;
    }

 
    let regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(email)) {
        alert("Digite um e-mail válido.");
        document.getElementById("email").focus();
        return false;
    }

    if (email.length > 100) {
        alert("O e-mail deve ter no máximo 100 caracteres.");
        document.getElementById("email").focus();
        return false;
    }

    if (contato.length < 3) {
        alert("O contato deve ter pelo menos 3 caracteres.");
        document.getElementById("contato").focus();
        return false;
    }

    if (contato.length > 100) {
        alert("O contato deve ter no máximo 100 caracteres.");
        document.getElementById("contato").focus();
        return false;
    }

    return true;
}