<?php 
	session_start();
	ob_start(); # Limpando o buffer de saida
	
	require_once 'config.php';
	require_once 'conexao.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/css/style.css">
    <title>Cadastrar</title>

</head>
<body>

    <?php
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        #var_dump($dados);

        if (isset($dados['SendCaduser'])) {
            
            # Verificando se os campos estão vazios
            if(empty($dados['nome']) || empty($dados['usuario']) || empty($dados['senha_usuario'])) {
                $_SESSION['msg'] = "<p class='error'>Todos os campos são obrigatórios!</p>";
            } else {

                # Verificando se o email já está cadastrado
                $query_usuario_esistente = "SELECT id FROM userz WHERE usuario = :usuario LIMIT 1";
                $check_usuario = $conn->prepare($query_usuario_esistente);
                $check_usuario->bindParam(":usuario", $dados['usuario']);
                $check_usuario->execute();

                if($check_usuario->rowCount() > 0) {
                    $_SESSION['msg'] = "<p class='error'>Esse email já está cadastrado!</p>";
                } else {

                    # Criptografar senha
                    $senha_cripto = password_hash($dados['usuario'], PASSWORD_DEFAULT);

                    # Inserindo novo usuári
                    $query_usuario = "INSERT INTO userz (nome, usuario, senha_usuario) VALUES (:nome, :usuario, :senha_usuario)";
                    $cad_usuario = $conn->prepare($query_usuario);
                    $cad_usuario->bindParam(":nome", $dados['nome']);
                    $cad_usuario->bindParam(":usuario", $dados['usuario']);
                    $cad_usuario->bindParam(":senha_usuario", $dados['senha_usuario']);
                    $cad_usuario->execute();

                    if($cad_usuario->rowCount()) {
                        $_SESSION['msg'] = "<p class='sucess'>Usuário cadastrado com sucesso!</p>";
                        header("Location: index.php");
                        exit;
                    } else {
                        $_SESSION['msg'] = "<p class='error'>Erro ao cadastrar usuário. Tente novamente.</p>";
                    }
                }
            }
        }

    ?>
    <div class="container">

        <form action="" method="POST" class="form">
            <h1>Cadastrar Usuário</h1>
            
            <label for="nome">Nome:</label>
            <input type="text" name="nome" placeholder="Digite seu nome"><br>

            <label for="email">Email:</label>
            <input type="email" name="usuario" name="nome" placeholder="Digite seu email"><br>
            
            <label for="senha">Senha:</label>
            <input type="password" name="senha_usuario" name="nome" placeholder="Digite sua senha"><br>
            
            <input type="submit" name="SendCaduser" value="Cadastrar">
            <a href="index.php" class="btnc btn-cadastrar">Login</a>
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