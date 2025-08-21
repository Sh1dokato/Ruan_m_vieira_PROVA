<?php
session_start();
require_once 'conexao.php';

if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_fornecedor = $_POST['id_fornecedor'];
    $nome_fornecedor = trim($_POST['nome_fornecedor']);
    $endereco = trim($_POST['endereco']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $contato = trim($_POST['contato']);
    
    $erros = [];
    
    // Validar o Nomme
    if (strlen($nome_fornecedor) < 3) {
        $erros[] = "O nome do fornecedor deve ter pelo menos 3 caracteres.";
    }
    if (strlen($nome_fornecedor) > 100) {
        $erros[] = "O nome do fornecedor deve ter no máximo 100 caracteres.";
    }

    // Validar o endereço
    if (strlen($endereco) < 5) {
        $erros[] = "O endereço deve ter pelo menos 5 caracteres.";
    }
    if (strlen($endereco) > 255) {
        $erros[] = "O endereço deve ter no máximo 255 caracteres.";
    }
    
    // Validar o telefone
    $telefone_numeros = preg_replace('/\D/', '', $telefone); 
    if (strlen($telefone_numeros) < 10) {
        $erros[] = "O telefone deve ter pelo menos 10 dígitos.";
    }
    if (strlen($telefone_numeros) > 11) {
        $erros[] = "O telefone deve ter no máximo 11 dígitos.";
    }
    
    // Validar o email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Digite um e-mail válido.";
    }
    if (strlen($email) > 100) {
        $erros[] = "O e-mail deve ter no máximo 100 caracteres.";
    }
    
    // Verificar se email já existe (exceto para o fornecedor atual)
    $sql_check = "SELECT id_fornecedor FROM fornecedor WHERE email = :email AND id_fornecedor != :id_fornecedor";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':email', $email);
    $stmt_check->bindParam(':id_fornecedor', $id_fornecedor);
    $stmt_check->execute();
    if ($stmt_check->fetch()) {
        $erros[] = "Este e-mail já está cadastrado para outro fornecedor.";
    }
    
    // Validação do contato
    if (strlen($contato) < 3) {
        $erros[] = "O contato deve ter pelo menos 3 caracteres.";
    }
    if (strlen($contato) > 100) {
        $erros[] = "O contato deve ter no máximo 100 caracteres.";
    }
    
    // Se não há erros, prossegue com a atualização
    if (empty($erros)) {
        $sql = "UPDATE fornecedor SET nome_fornecedor = :nome_fornecedor, endereco = :endereco, telefone = :telefone, email = :email, contato = :contato WHERE id_fornecedor = :id_fornecedor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_fornecedor', $id_fornecedor);
        $stmt->bindParam(':nome_fornecedor', $nome_fornecedor);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contato', $contato);

        if($stmt->execute()){
            echo "<script>alert('Fornecedor alterado com sucesso!');window.location.href='buscar_fornecedor.php';</script>";
        }else{
            echo "<script>alert('Erro ao alterar fornecedor!');window.location.href='alterar_fornecedor.php';</script>";
        }
    } else {
        echo "<script>alert('" . implode("\\n", $erros) . "');window.location.href='alterar_fornecedor.php?id=$id_fornecedor';</script>";
    }
}else{
    echo "<script>alert('Método não permitido!');window.location.href='principal.php';</script>";
}
?>
