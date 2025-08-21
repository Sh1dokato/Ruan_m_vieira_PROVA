<?php
require_once 'conexao.php';
require_once 'menudropdown.php';

//verifica se o usuario tem permissao
//supondo que o perfil 1 seja o administrador

if($_SESSION['perfil']!=1){
    echo "Acesso Negado!";
}

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $id_perfil = $_POST['id_perfil'];
    
    // Validação do lado do servidor
    $erros = [];
    
    // Validação do nome
    if (strlen($nome) < 3) {
        $erros[] = "O nome deve ter pelo menos 3 caracteres.";
    }
    if (strlen($nome) > 50) {
        $erros[] = "O nome deve ter no máximo 50 caracteres.";
    }
    
    // Validação para não permitir números no nome
    if (!preg_match('/^[a-zA-ZÀ-ÿ\s]+$/', $nome)) {
        $erros[] = "O nome não pode conter números ou caracteres especiais.";
    }
    
    // Validação do email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Digite um e-mail válido.";
    }
    if (strlen($email) > 60) {
        $erros[] = "O e-mail deve ter no máximo 60 caracteres.";
    }
    
    // Verificar se email já existe
    $sql_check = "SELECT id_usuario FROM usuario WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':email', $email);
    $stmt_check->execute();
    if ($stmt_check->fetch()) {
        $erros[] = "Este e-mail já está cadastrado.";
    }
    
    // Validação da senha
    if (strlen($senha) < 8) {
        $erros[] = "A senha deve ter pelo menos 8 caracteres.";
    }
    if (strlen($senha) > 50) {
        $erros[] = "A senha deve ter no máximo 50 caracteres.";
    }
    
    // Validação do perfil
    if (empty($id_perfil) || !in_array($id_perfil, ['1', '2', '3', '4'])) {
        $erros[] = "Selecione um perfil válido.";
    }
    
    // Se não há erros, prossegue com o cadastro
    if (empty($erros)) {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        $sql="INSERT INTO usuario(nome,email,senha,id_perfil) VALUES (:nome,:email,:senha,:id_perfil)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome',$nome);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':senha',$senha_hash);
        $stmt->bindParam(':id_perfil',$id_perfil);

        if($stmt->execute()){
            echo "<script>alert('Usuario cadastrado com sucesso!');</script>";
        }else{
            echo "<script>alert('Erro ao cadastrar usuario');</script>";
        }
    } else {
        echo "<script>alert('" . implode("\\n", $erros) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuario</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Cadastrar Usuario</h2>
                    </div>
                    <div class="card-body">
                        <form action="cadastrar_usuario.php" method="POST" onsubmit="return validarUsuario()">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" class="form-control" name="nome" id="nome" required 
                                       onblur="validarCampo(this, 'nome')" oninput="this.classList.remove('is-invalid', 'is-valid')"
                                       onkeypress="return /[a-zA-ZÀ-ÿ\s]/i.test(event.key)" 
                                       placeholder="Digite apenas letras">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" name="email" id="email" required 
                                       onblur="validarCampo(this, 'email')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" class="form-control" name="senha" id="senha" required 
                                       onblur="validarCampo(this, 'senha')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                            </div>

                            <div class="mb-3">
                                <label for="id_perfil" class="form-label">Perfil:</label>
                                <select class="form-select" name="id_perfil" id="id_perfil" required>
                                    <option value="">Selecione um perfil</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Secretaria</option>
                                    <option value="3">Almoxarife</option>
                                    <option value="4">Cliente</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary me-md-2">Salvar</button>
                                <button type="reset" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </form>
                        
                        <div class="mt-3">
                            <a href="principal.php" class="btn btn-outline-primary">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
        <br>
        <address><center>Ruan de Mello Vieira</center></address>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

