<?php 
    session_start();
	ob_start();

    require_once 'config.php';
    require_once 'conexao.php';
    
    # Recebendo a chave 
    $chave_recuperar_senha = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);
    #var_dump($chave_recuperar_senha);
    #echo '<hr>';

    if(empty($chave_recuperar_senha)) {
        $_SESSION['msg'] = "<p class='error'>Erro: Chave inválida !</p>";
        header("Location: index.php");
        exit;
    } else {

        $query_usuario = "SELECT id FROM userz WHERE chave_recuperar_senha = :chave_recuperar_senha LIMIT 1";
        $result_usuario = $conn->prepare($query_usuario);
        $result_usuario->bindParam(":chave_recuperar_senha", $chave_recuperar_senha);
        $result_usuario->execute();

        if($result_usuario->rowCount() === 0) {
            
            $_SESSION['msg'] = "<p class='error'>Erro: Chave inválida!</p>";
            header("Location: index.php");
            exit;
        } else {
            $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/css/style.css">
    <title>Atualizar Senha</title>
</head>
<body>
    
    <?php 

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if(!empty($dados['SendNovaSenha'])) {
            #var_dump($dados);
            #echo '<hr>';

            # Criptrografando senha
            $senha_usuario = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);
            $chave_recuperar_senha = NULL;

            # Editar usuário e salvar a nova senha 
            $query_up_usuario = "UPDATE userz SET senha_usuario = :senha_usuario, chave_recuperar_senha = :chave_recuperar_senha WHERE id = :id LIMIT 1";
            $editar_usuario = $conn->prepare($query_up_usuario);
            $editar_usuario->bindParam(":senha_usuario", $senha_usuario);
            $editar_usuario->bindParam(":chave_recuperar_senha", $chave_recuperar_senha);
            $editar_usuario->bindParam(":id", $row_usuario['id']);
            $editar_usuario->execute();

            if($editar_usuario) {
                $_SESSION['msg'] = "<p class='sucess'>Senha Atualizada com sucesso!</p>";
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['msg'] = "<p class='error'>Erro: Tente novamente !</p>";
            }

        }


    ?>

    <div class="container">

        <form action="" method="POST" class="form">
            
            <label for="">Senha</label>
            <input type="password" name="senha_usuario" placeholder="Nova senha"><br>
            <input type="submit" value="Atualizar" name="SendNovaSenha">
            <p class="lembrou">lembrou a senha ? <a href="index.php" class="lembrou-btn">clique aqui</a> para logar</p>
            <?php 
                if(isset($_SESSION['msg'])):
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                endif;
            ?>
        </form>
    </div>

</body>
</html>