<?php
require_once 'menudropdown.php';
require_once 'conexao.php';

//verifica se o usuario tem permissão de adm ou secretaria
if($_SESSION['perfil'] !=1 && $_SESSION['perfil']!=2){
    echo"<script>alert('Acesso Negado!');window.location.href='principal.php';</script>";
    exit();
}

$fornecedor = []; //inicializa a variavel para evitar erros

//se o formulario for enviado, busca o fornecedor pelo ID ou Nome
if($_SERVER["REQUEST_METHOD"]=="POST" && !empty($_POST['busca'])){
    $busca = trim($_POST['busca']);

    //verifica se a busca é um numero ou um nome
    if(is_numeric($busca)){
        $sql="SELECT * FROM fornecedor WHERE id_fornecedor = :busca ORDER BY nome_fornecedor ASC";
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':busca',$busca, PDO::PARAM_INT);
    }else{
        $sql="SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome ORDER BY nome_fornecedor ASC";
        $stmt=$pdo->prepare($sql);
        $stmt->bindValue(':busca_nome',"$busca%", PDO::PARAM_STR);
    }
}else{
    $sql="SELECT * FROM fornecedor ORDER BY nome_fornecedor ASC";
    $stmt=$pdo->prepare($sql);
}
$stmt->execute();
$fornecedores = $stmt->fetchALL(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Fornecedor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center mb-4">Lista de Fornecedores</h2>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="buscar_fornecedor.php" method="POST" class="row g-3">
                            <div class="col-md-8">
                                <label for="busca" class="form-label">Digite o ID ou Nome (opcional):</label>
                                <input type="text" class="form-control" id="busca" name="busca">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Pesquisar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if(!empty($fornecedores)):?>
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
                                <?php foreach($fornecedores as $fornecedor):?>
                                    <tr>
                                        <td><?=htmlspecialchars($fornecedor['id_fornecedor'])?></td>
                                        <td><?=htmlspecialchars($fornecedor['nome_fornecedor'])?></td>
                                        <td><?=htmlspecialchars($fornecedor['endereco'])?></td>
                                        <td><?=htmlspecialchars($fornecedor['telefone'])?></td>
                                        <td><?=htmlspecialchars($fornecedor['email'])?></td>
                                        <td><?=htmlspecialchars($fornecedor['contato'])?></td>
                                        <td>
                                            <a href="alterar_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>" class="btn btn-warning btn-sm">Alterar</a>
                                            <a href="excluir_fornecedor.php?id=<?=htmlspecialchars($fornecedor['id_fornecedor'])?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este fornecedor?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                <?php else:?>
                    <div class="alert alert-info text-center">
                        <p>Nenhum fornecedor encontrado.</p>
                    </div>
                <?php endif;?>
                
                <div class="mt-3">
                    <a href="principal.php" class="btn btn-outline-primary">VOLTAR</a>
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
