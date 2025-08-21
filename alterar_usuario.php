<?php
require_once 'menudropdown.php';
require_once 'conexao.php';

//verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso Negado');window.location.href='principal.php';</script>";
    exit();
}

//inicializa variáveis
$usuario = null;

//verifica se foi passado um ID via GET (vindo da página de busca)
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_usuario = $_GET['id'];
    $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(!$usuario){
        echo "<script>alert('Usuário não encontrado');</script>";
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST['busca_usuario'])){
        $busca = trim($_POST['busca_usuario']);

        //verifica se a busca é um numero (id) ou um nome
        if(is_numeric($busca)){
            $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            //adiona o caractere de porcentagem para busca parcial
            $buscaLike = "%$busca%";
            $stmt->bindParam(':busca_nome', $buscaLike, PDO::PARAM_STR);
        }

        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        //se o usuario não for encontrado, exibe um alerta
        if(!$usuario){
            echo "<script>alert('Usuario não encontrado');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuario</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <script src="validacoes.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Alterar Usuario</h2>
                    </div>
                    <div class="card-body">
                        <form action="alterar_usuario.php" method="POST" class="mb-4">
                            <div class="mb-3">
                                <label for="busca_usuario" class="form-label">Digite o ID ou nome do usuario:</label>
                                <input type="text" class="form-control" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSugestoes()">
                            </div>
                            <!-- div para exibir sugestões de usuarios -->
                            <div id="sugestoes"></div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>

                        <?php if($usuario): ?>
                            <!-- formulario para alterar usuario -->
                            <form action="processa_alteracao_usuario.php" method="POST" onsubmit="return validarAlteracaoUsuario()">
                                <input type="hidden" name="id_usuario" value="<?=htmlspecialchars($usuario['id_usuario'])?>">

                                <div class="mb-3">
                                    <label for="nome" class="form-label">Nome:</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?=htmlspecialchars($usuario['nome'])?>" required 
                                           onblur="validarCampo(this, 'nome')" oninput="this.classList.remove('is-invalid', 'is-valid')"
                                           onkeypress="return /[a-zA-ZÀ-ÿ\s]/i.test(event.key)" 
                                           placeholder="Digite apenas letras">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail:</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?=htmlspecialchars($usuario['email'])?>" required 
                                           onblur="validarCampo(this, 'email')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                </div>

                                <div class="mb-3">
                                    <label for="id_perfil" class="form-label">Perfil:</label>
                                    <select class="form-select" id="id_perfil" name="id_perfil" required>
                                        <option value="">Selecione um perfil</option>
                                        <option value="1" <?=$usuario['id_perfil'] == 1 ?'selected':''?>>Administrador</option>
                                        <option value="2" <?=$usuario['id_perfil'] == 2 ?'selected':''?>>Secretaria</option>
                                        <option value="3" <?=$usuario['id_perfil'] == 3 ?'selected':''?>>Almoxarife</option>
                                        <option value="4" <?=$usuario['id_perfil'] == 4 ?'selected':''?>>Cliente</option>
                                    </select>
                                </div>

                                <!-- se o usuario logado for admin, exibir a opção de alterar senha -->
                                <?php if ($_SESSION['perfil'] == 1): ?>
                                    <div class="mb-3">
                                        <label for="nova_senha" class="form-label">Nova Senha:</label>
                                        <input type="password" class="form-control" id="nova_senha" name="nova_senha" 
                                               onblur="validarCampo(this, 'senha')" oninput="this.classList.remove('is-invalid', 'is-valid')">
                                    </div>
                                <?php endif; ?>
                                
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
</body>
</html>