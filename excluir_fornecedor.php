<?php
require_once 'menudropdown.php';
require 'conexao.php';

if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado!');window.location.href='principal.php'</script>";
    exit();
}

$fornecedores = [];


$sql = "SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_fornecedor = $_GET['id'];

    $sql = "DELETE FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$id_fornecedor,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Fornecedor excluido com Sucesso!');window.location.href='excluir_fornecedor.php'</script>";
        
    }else{
        echo "<script>alert('Erro ao excluir o fornecedor!')</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Fornecedor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Excluir Fornecedor</h2>
                
                <?php if(!empty($fornecedores)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Endereço</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Contato</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($fornecedores as $fornecedor): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($fornecedor['id_fornecedor'])?></td>
                                        <td><?= htmlspecialchars($fornecedor['nome_fornecedor'])?></td>
                                        <td><?= htmlspecialchars($fornecedor['endereco'])?></td>
                                        <td><?= htmlspecialchars($fornecedor['telefone'])?></td>
                                        <td><?= htmlspecialchars($fornecedor['email'])?></td>
                                        <td><?= htmlspecialchars($fornecedor['contato'])?></td>
                                        <td>
                                            <a href="excluir_fornecedor.php?id=<?= htmlspecialchars($fornecedor['id_fornecedor'])?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                <?php else:?>
                    <div class="alert alert-info text-center">
                        <p>Nenhum fornecedor encontrado</p>
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
