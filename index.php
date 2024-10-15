<?php 
	session_start();
	ob_start(); # Limpando o buffer de saida
	
	
	use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
	
	require_once 'conexao.php';

	# Definindo um fuso horário padrão
	date_default_timezone_set('America/Sao_Paulo');
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="./assests/css/style.css">
</head>
<body>
	

	<?php 

		# Criptografando senha
		#echo password_hash(123456, PASSWORD_DEFAULT);
		# Recebendo os dados do formulário
		$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

		if(!empty($dados['SendLogin'])) {
			// var_dump($dados);

			# Recuperando os dados no banco de dados
			$query_usuario = "SELECT id, nome, usuario, senha_usuario FROM userz WHERE usuario = :usuario LIMIT 1";
			$result_usuario = $conn->prepare($query_usuario);
			$result_usuario->bindParam(":usuario", $dados['usuario']);
			$result_usuario->execute();

			# Verificando se encontrou registro no banco de dados
			if($result_usuario->rowCount() != 0) {

				# Ler os registros retornado no banco de dados
				$row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
				// var_dump($row_usuario);


				if(password_verify($dados['senha_usuario'], $row_usuario['senha_usuario'])) {

					# Salvar os dados do usuário na sessão
					$_SESSION['id'] = $row_usuario['id'];
					$_SESSION['usuario'] = $row_usuario['usuario'];

					# Recuperando data atual 
					$data = date('Y-m-d H:i:s');

					# Gerando número randômico entre 10000 e 99999
					$codigo_autenticacao = mt_rand(100000, 999999);
					var_dump($codigo_autenticacao);

					# Salvando código gerado no banco de dados
					$query_up_usuario = "UPDATE userz SET codigo_autenticacao = :codigo_autenticacao, data_codigo_autenticacao = :data_codigo_autenticacao WHERE id = :id LIMIT 1";
					$result_up_usuario = $conn->prepare($query_up_usuario);
					$result_up_usuario->bindParam(":codigo_autenticacao", $codigo_autenticacao);
					$result_up_usuario->bindParam(":data_codigo_autenticacao", $data);
					$result_up_usuario->bindParam(":id", $row_usuario['id']);
					$result_up_usuario->execute();

					# Incluindo autoload PhpMailer somente quando o email estiver correto;
					require './lib/vendor/autoload.php';
					# Instânciando PHPMailer
					$mail = new PHPMailer(true);

					try {
						
						# Permitir envio de email com caracteres especiais
						$mail->CharSet = 'UTF-8';


						//Server settings
						// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
						$mail->SMTPDebug = 2;                      //Enable verbose debug output
						#$mail->SMTPDebug = false;                      //Enable verbose debug output
						$mail->isSMTP();                                            //Send using SMTP
						$mail->Host       = 'localhost';                     //Set the SMTP server to send through
						$mail->SMTPAuth   = false; #true                                  //Enable SMTP authentication
						#$email->Username = '800552a6543fea';
       					#$email->Password = '********289c';                               //SMTP password
						#$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
						// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
						$mail->Port       = 1025; #587                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
						

						//Recipients
						$mail->setFrom('desenvolvimentophpteste@gmail.com', 'Atendimento');
						$mail->addAddress($row_usuario['usuario'], $row_usuario['nome']);     //Add a recipient
						

						//Content
						$mail->isHTML(true);                                  //Set email format to HTML
						$mail->Subject = 'Aqui está seu código de verificação de 6 dígitos';
						$mail->Body    = "Ola, " . $row_usuario['nome'] . ", Autenticação multifator.<br><br> seu código de verificação de 6 dígitos é $codigo_autenticacao  <br><br> Esse código foi enviado para verificar seu login.";

						$mail->AltBody = "Ola, " . $row_usuario['nome'] . ", Autenticação multifator.\n\n seu código de verificação de 6 dígitos é $codigo_autenticacao  \n\n Esse código foi enviado para verificar seu login.";

						$mail->send();
    					
						header("Location: validar_codigo.php");
						exit;


					} catch (Exception $e) {
						#echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						$_SESSION['msg'] = "<p style='color: #f00;'>Erro: Email não enviado</p>";
					}

				} else {
					$_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário ou senha invalidos</p>";
				}

			} else {
				$_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário ou senha invalidos</p>";
			}
		}

		

	?>

	<div class="container">
		<form action="" method="POST">
			<h2>Login dois Fatores</h2>
			<label for="">Usuário</label>
			<input type="text" name="usuario" placeholder="Digite o Usuário"><br><br>
			
			<label for="">Senha</label>
			<input type="password" name="senha_usuario" placeholder="Digite a Senha"><br><br>
			
			<input type="submit" value="Entrar" name="SendLogin"><br>
			<?php 
				if(isset($_SESSION['msg'])):
					echo $_SESSION['msg'];
					unset($_SESSION['msg']);
				endif;
			?>
		</form>

	</div>


	
	<div class="user-info">
        <h3>Usuário Teste</h3>
        <p>Nome: adm@gmail.com</p>
        <p>Senha: 12356</p>
    </div>
</body>
</html>