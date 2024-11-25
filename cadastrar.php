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
    <h2>Cadastrar Usuário</h2>

    <?php
        
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        #var_dump($dados);

        if (isset($dados['SendCaduser'])) {
            echo 'okkk';

            # Criptrografar senha
            $senha_cripto = password_hash($dados['senha_usuario'], PASSWORD_DEFAULT);


            $query_usuario = "INSERT INTO userz (nome, usuario, senha_usuario) VALUES (:nome, :usuario, :senha_usuario)";
            $cad_usuario = $conn->prepare($query_usuario);
            $cad_usuario->bindParam(":nome", $dados['nome']);
            $cad_usuario->bindParam(":usuario", $dados['usuario']);
            $cad_usuario->bindParam(":senha_usuario", $senha_cripto);
            $cad_usuario->execute();

            if($cad_usuario->rowCount()) {
                $_SESSION['msg'] = "<p style='color: green'>Usuário cadastrado</p>";


                header("Location: index.php");
                exit;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro no cadastro</p>";
            }
        }



        
        if(isset($_SESSION['msg'])):
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
		endif;
			

    ?>

    <form action="" method="POST">
        Nome: <input type="text" name="nome"><br>
        Email: <input type="email" name="usuario"><br>
        Senha: <input type="password" name="senha_usuario"><br>
        <button type="submit" name="SendCaduser">Cadastrar</button>
    </form>

    <a href="index.php" class="btnc btn-cadastrar">Login</a>
</body>
</html>