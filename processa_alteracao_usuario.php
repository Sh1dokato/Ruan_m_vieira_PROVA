<?php
session_start();
require 'conexao.php';

if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_POST['id_usuario'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $id_perfil = $_POST['id_perfil'];
    $nova_senha = $_POST['nova_senha'] ?? '';
    
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
    
    // Verificar se email já existe (exceto para o usuário atual)
    $sql_check = "SELECT id_usuario FROM usuario WHERE email = :email AND id_usuario != :id_usuario";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(':email', $email);
    $stmt_check->bindParam(':id_usuario', $id_usuario);
    $stmt_check->execute();
    if ($stmt_check->fetch()) {
        $erros[] = "Este e-mail já está cadastrado para outro usuário.";
    }
    
    // Validação do perfil
    if (empty($id_perfil) || !in_array($id_perfil, ['1', '2', '3', '4'])) {
        $erros[] = "Selecione um perfil válido.";
    }
    
    // Validação da nova senha (se fornecida)
    if (!empty($nova_senha)) {
        if (strlen($nova_senha) < 8) {
            $erros[] = "A nova senha deve ter pelo menos 8 caracteres.";
        }
        if (strlen($nova_senha) > 50) {
            $erros[] = "A nova senha deve ter no máximo 50 caracteres.";
        }
    }
    
    // Se não há erros, prossegue com a atualização
    if (empty($erros)) {
        $senha_hash = !empty($nova_senha) ? password_hash($nova_senha, PASSWORD_DEFAULT) : null;

        //atualiza os dados do usuario
        if($senha_hash){
            $sql = "UPDATE usuario SET nome=:nome,email=:email,id_perfil=:id_perfil,senha=:senha WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':senha',$senha_hash);
        }else{
            $sql="UPDATE usuario SET nome = :nome,email = :email,id_perfil=:id_perfil WHERE id_usuario = :id";
            $stmt = $pdo->prepare($sql);
        }
        $stmt->bindParam(':nome',$nome);
        $stmt->bindParam(':email',$email);
        $stmt->bindParam(':id_perfil',$id_perfil);
        $stmt->bindParam(':id',$id_usuario);

        if($stmt->execute()){
            echo "<script>alert('Usuario atualizado com sucesso!');window.location.href='buscar_usuario.php';</script>";
        }else{
            echo "<script>alert('Erro ao atualizar usuario');window.location.href='alterar_usuario.php?id=$id_usuario';</script>";
        }
    } else {
        echo "<script>alert('" . implode("\\n", $erros) . "');window.location.href='alterar_usuario.php?id=$id_usuario';</script>";
    }
}
?>