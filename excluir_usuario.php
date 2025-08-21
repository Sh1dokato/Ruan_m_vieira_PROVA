<?php
require 'conexao.php';
require_once 'menudropdown.php';

if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
    exit();
}

//inicializa variavel para armazenar usuarios
$usuarios = [];

//busca todos os usuarios cadastrados em ordem alfabetica
$sql = "SELECT * FROM usuario ORDER BY nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

//se um id for passado via get exclui o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];

    //exclui o usuario do banco de dados
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$id_usuario,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Usuario excluido com Sucesso!');window.location.href='excluir_usuario.php'</script>";
        
    }else{
        echo "<script>alert('Erro ao excluir o usuario!')</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuario</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Excluir Usuario</h2>
                
                <?php if(!empty($usuarios)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Perfil</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($usuario['id_usuario'])?></td>
                                        <td><?= htmlspecialchars($usuario['nome'])?></td>
                                        <td><?= htmlspecialchars($usuario['email'])?></td>
                                        <td><?= htmlspecialchars($usuario['id_perfil'])?></td>
                                        <td>
                                            <a href="excluir_usuario.php?id=<?= htmlspecialchars($usuario['id_usuario'])?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuario?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                <?php else:?>
                    <div class="alert alert-info text-center">
                        <p>Nenhum usuario encontrado</p>
                    </div>
                <?php endif;?>

                <div class="mt-3">
                    <a href="principal.php" class="btn btn-outline-primary">Voltar</a>
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