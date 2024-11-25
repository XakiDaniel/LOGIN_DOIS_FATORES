<?php 
    session_start();
    ob_start(); # Limpando o buffer de saida

    require_once 'config.php';
    require_once 'conexao.php';

	# Definindo um fuso horário padrão
	date_default_timezone_set('America/Sao_Paulo');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/css/style.css">
    <title>Validar Login</title>
</head>
<body>
    <?php

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if(!empty($dados['ValCodigo'])) {
            var_dump($dados);

            # Recuperando os dados no banco de dados
			$query_usuario = "SELECT id, nome, usuario, senha_usuario 
                            FROM userz 
                            WHERE id = :id 
                            AND usuario = :usuario
                            AND codigo_autenticacao = :codigo_autenticacao
                            LIMIT 1";
            
            $result_usuario = $conn->prepare($query_usuario);
			$result_usuario->bindParam(":id", $_SESSION['id']);
			$result_usuario->bindParam(":usuario", $_SESSION['usuario']);
			$result_usuario->bindParam(":codigo_autenticacao", $dados['codigo_autenticacao']);
			$result_usuario->execute();


            # Verificando se encontrou registro no banco de dados
			if($result_usuario->rowCount() != 0) {

				# Ler os registros retornado no banco de dados
				$row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
				// var_dump($row_usuario);
                $query_up_usuario = "UPDATE userz SET codigo_autenticacao = :NULL, data_codigo_autenticacao = :NULL WHERE id = :id LIMIT 1";
				$result_up_usuario = $conn->prepare($query_up_usuario);
                $result_up_usuario->bindParam(":id", $_SESSION['id']);
                $result_up_usuario->execute();

                $_SESSION['nome'] = $row_usuario['nome'];
				$_SESSION['codigo_autenticacao'] = true;

                header("Location: dashboard.php");
                exit;

            } else {
                $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Código inválido</p>";
                header('Location: validar_codigo.php');
                exit;
            }
        }
    ?>


    <div class="container">
		<form action="" method="POST">
			<h2>Digite o código enviado no e-mail cadastrado</h2>
			<label for="">Código</label>
		    <input type="number" name="codigo_autenticacao" placeholder="Digite o Código"><br><br>

		    <input type="submit" value="validar" name="ValCodigo"><br>
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