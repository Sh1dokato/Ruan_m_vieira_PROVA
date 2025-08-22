<?php
require_once 'menudropdown.php';
require_once 'conexao.php';

if ($_SESSION['perfil'] != 1) {
    echo "Acesso Negado!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_fornecedor = trim($_POST['nome_fornecedor']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $contato = trim($_POST['contato']);

    $erros = [];

    if (strlen($nome_fornecedor) < 3) $erros[] = "O nome do fornecedor deve ter pelo menos 3 caracteres.";
    if (strlen($nome_fornecedor) > 100) $erros[] = "O nome do fornecedor deve ter no máximo 100 caracteres.";

    if (strlen($endereco) < 5) $erros[] = "O endereço deve ter pelo menos 5 caracteres.";
    if (strlen($endereco) > 255) $erros[] = "O endereço deve ter no máximo 255 caracteres.";

    $telefone_numeros = preg_replace('/\D/', '', $telefone);
    if (strlen($telefone_numeros) < 10) $erros[] = "O telefone deve ter pelo menos 10 dígitos.";
    if (strlen($telefone_numeros) > 11) $erros[] = "O telefone deve ter no máximo 11 dígitos.";

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Digite um e-mail válido.";
    if (strlen($email) > 100) $erros[] = "O e-mail deve ter no máximo 100 caracteres.";

    $sql_check = "SELECT id_fornecedor FROM fornecedor WHERE email = :email";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':email', $email);
    $stmt_check->execute();
    if ($stmt_check->fetch()) $erros[] = "Este e-mail já está cadastrado.";

    if (strlen($contato) < 3) $erros[] = "O contato deve ter pelo menos 3 caracteres.";
    if (strlen($contato) > 100) $erros[] = "O contato deve ter no máximo 100 caracteres.";

    if (empty($erros)) {
        $sql = "INSERT INTO fornecedor (nome_fornecedor, endereco, telefone, email, contato)
                VALUES (:nome_fornecedor, :endereco, :telefone, :email, :contato)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contato', $contato);

        if ($stmt->execute()) {
            echo "<script>alert('Fornecedor cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar fornecedor');</script>";
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
    <title>Cadastrar Fornecedor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Cadastrar Fornecedor</h2>
                </div>
                <div class="card-body">
                    <form action="cadastrar_fornecedor.php" method="POST" onsubmit="return validarFornecedor()">
                        <div class="mb-3">
                            <label for="nome_fornecedor" class="form-label">Nome do Fornecedor:</label>
                            <input type="text" class="form-control" name="nome_fornecedor" id="nome_fornecedor" required 
                                   onblur="validarCampo(this, 'nome_fornecedor')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço:</label>
                            <input type="text" class="form-control" name="endereco" id="endereco" required 
                                   onblur="validarCampo(this, 'endereco')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone:</label>
                            <input type="text" class="form-control" name="telefone" id="telefone" required 
                                   onblur="validarCampo(this, 'telefone')" 
                                   oninput="this.classList.remove('is-invalid', 'is-valid')"
                                   placeholder="(11) 99999-9999">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" required 
                                   onblur="validarCampo(this, 'email')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                        </div>

                        <div class="mb-3">
                            <label for="contato" class="form-label">Contato:</label>
                            <input type="text" class="form-control" name="contato" id="contato" required 
                                   onblur="validarCampo(this, 'contato')" oninput="this.classList.remove('is-invalid', 'is-valid')">
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

<br><br>
<address><center>Ruan de Mello Vieira</center></address>

<!-- Scripts -->
<script src="js/bootstrap.bundle.min.js"></script>
<script src="validacoes.js"></script>
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const telefoneInput = document.getElementById("telefone");
        if (telefoneInput) {
            Inputmask({ mask: "(99) 99999-9999" }).mask(telefoneInput);
        }
    });
</script>
</body>
</html>
