<?php
require_once 'menudropdown.php';
require_once 'conexao.php';

//verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

//inicializa variáveis
$fornecedor = null;

//verifica se foi passado um ID via GET (vindo da página de busca)
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_fornecedor = $_GET['id'];
    $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_fornecedor, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$fornecedor){
        echo "<script>alert('Fornecedor não encontrado');</script>";
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST['busca_fornecedor'])){
        $busca = trim($_POST['busca_fornecedor']);

        //verifica se a busca é um numero (id) ou um nome
        if(is_numeric($busca)){
            $sql = "SELECT * FROM fornecedor WHERE id_fornecedor = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM fornecedor WHERE nome_fornecedor LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            //adiona o caractere de porcentagem para busca parcial
            $buscaLike = "%$busca%";
            $stmt->bindParam(':busca_nome', $buscaLike, PDO::PARAM_STR);
        }

        $stmt->execute();
        $fornecedor = $stmt->fetch(PDO::FETCH_ASSOC);

        //se o fornecedor não for encontrado, exibe um alerta
        if(!$fornecedor){
            echo "<script>alert('Fornecedor não encontrado');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Fornecedor</title>
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
                        <h2 class="text-center">Alterar Fornecedor</h2>
                    </div>
                    <div class="card-body">
                        <form action="alterar_fornecedor.php" method="POST" class="mb-4">
                            <div class="mb-3">
                                <label for="busca_fornecedor" class="form-label">Digite o ID ou nome do fornecedor:</label>
                                <input type="text" class="form-control" id="busca_fornecedor" name="busca_fornecedor" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>

                        <?php if($fornecedor): ?>
                            <!-- formulario para alterar fornecedor -->
                            <form action="processa_alteracao_fornecedor.php" method="POST" onsubmit="return validarAlteracaoFornecedor()">
                                <input type="hidden" name="id_fornecedor" value="<?=htmlspecialchars($fornecedor['id_fornecedor'])?>">

                                <div class="mb-3">
                                    <label for="nome_fornecedor" class="form-label">Nome do Fornecedor:</label>
                                    <input type="text" class="form-control" id="nome_fornecedor" name="nome_fornecedor" value="<?=htmlspecialchars($fornecedor['nome_fornecedor'])?>" required 
                                           onblur="validahis, 'nome_fornecedor')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                </div>

                                <div class="mb-3">
                                    <label for="endereco" class="form-label">Endereço:</label>
                                    <input type="text" class="form-control" id="endereco" name="endereco" value="<?=htmlspecialchars($fornecedor['endereco'])?>" required 
                                           onblur="validarCampo(this, 'endereco')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                </div>

                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone:</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?=htmlspecialchars($fornecedor['telefone'])?>" required 
                                           onblur="validarCampo(this, 'telefone')" oninput="this.classList.remove('is-invalid', 'is-valid')"
                                           onkeyup="permitirApenasNumerosTelefone(this)" placeholder="(11) 99999-9999">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?=htmlspecialchars($fornecedor['email'])?>" required 
                                           onblur="validarCampo(this, 'email')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                </div>

                                <div class="mb-3">
                                    <label for="contato" class="form-label">Contato:</label>
                                    <input type="text" class="form-control" id="contato" name="contato" value="<?=htmlspecialchars($fornecedor['contato'])?>" required 
                                           onblur="validarCampo(this, 'contato')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-success me-md-2">Alterar</button>
                                    <button type="reset" class="btn btn-secondary">Cancelar</button>
                                </div>
                            </form>
                        <?php endif; ?>
                        
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
    <script src="https://cdn.jsdelivr.net/npm/inputmask/inputmask.min.js"></script>
    <script> 
        Inputmask({ mask: "(99) 99999-9999"}).mask("telefone") 
    </script>
</body>
</html>
