<?php 
	session_start();
	ob_start(); # Limpando o buffer de saida
    use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;
    
    require_once 'config.php';
	require_once 'conexao.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assests/css/style.css">
    <title>Recuperar Senha</title>
</head>
<body>
    <?php 

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if(!empty($dados['SendRecupSenha'])) {
            #var_dump($dados);

            # Verificar se existe usuário no banco de dados 
            $query_usuario = "SELECT id, nome, usuario FROM userz WHERE usuario = :usuario LIMIT 1";

            $result_usuario = $conn->prepare($query_usuario);
            $result_usuario->bindParam(":usuario", $dados['usuario']);
            $result_usuario->execute();

            if ($result_usuario->rowCount() != 0) {
                // Recupera os dados do usuário como array associativo
                $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
                #var_dump($row_usuario);
                #echo '<hr>';
            
                // Gerando chave para recuperar a senha
                $chave_recuperar_senha = password_hash($row_usuario['id'] . $row_usuario['usuario'], PASSWORD_DEFAULT); // Corrigido
                #var_dump($chave_recuperar_senha);
                #echo '<hr>';
                
            
                // Atualizando o banco com a chave gerada
                $query_up_usuario = "UPDATE userz SET chave_recuperar_senha = :chave_recuperar_senha WHERE id = :id LIMIT 1";
                $editar_usuario = $conn->prepare($query_up_usuario);
                $editar_usuario->bindParam(":chave_recuperar_senha", $chave_recuperar_senha);
                $editar_usuario->bindParam(":id", $row_usuario['id']);
                $editar_usuario->execute();
            
                if ($editar_usuario) {

                    # Gerando o link para recuperar senha 
                    $link = "http://localhost/LOGIN_DOIS_FATORES/atualizar_senha.php?chave=$chave_recuperar_senha";
                    #var_dump($link);
                    #echo '<hr>';

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
						
                        # Verificando se o email é válido antes de adicionar
						if(filter_var($row_usuario['usuario'], FILTER_VALIDATE_EMAIL)) {
							$mail->addAddress($row_usuario['usuario'], $row_usuario['nome']); // Adiciona o destinatário
						} else {
							$_SESSION['msg'] = "<p class='error'>Erro: Endereço de e-mail inválido</p>";
						}

                        
						//Recipients
						$mail->setFrom('desenvolvimentophpteste@gmail.com', 'Atendimento');
						#$mail->addAddress($row_usuario['usuario'], $row_usuario['nome']);     //Add a recipient (ANTES ERA ASSIM TALVEZ APAGAR)
						

						//Content
						$mail->isHTML(true);                                  //Set email format to HTML
						$mail->Subject = 'Recuperar senha';
						$mail->Body    = "Ola, " . $row_usuario['nome'] . ".<br><br>Você solicitou a açteração da senha.<br><br>Para continuar o processo de recuperação de sua senha clique no link abrixo ou colo o endereço no seu navegador: <br><br><a href='" .$link . "'>". $link . "</a><br><br>Se você não solicitou esse alteração é necessária. Sua senha permanecer a mesma  até que você ative este código.<br><br>";

						$mail->AltBody = "Ola, " . $row_usuario['nome'] . "\n\nVocê solicitou a alteração de senha.\n\nPara continuar o processo de recuperação da sua senha, clique no link abaixo ou cole o endereço no seu navegador: \n\n" . $link . "\n\nSe você não solicitou essa alteração, nenhuma alteração é necessária. Sua senha permanecera a mesma até que você ative este código";

						$mail->send();
    					
                        $_SESSION['msg'] = "<p class='sucess'>Email de recuperação enviado com sucesso</p>";
						header("Location: index.php");
						exit;


					} catch (Exception $e) {
						#echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
						$_SESSION['msg'] = "<p class='error'>Erro: Email não enviado</p>";
					}

                } else {
                    $_SESSION['msg'] = "<p class='error'>Erro tente novamente !</p>";
                }
            } else {
                $_SESSION['msg'] = "<p class='error'>Usuário não encontrado !</p>";
            }
            

        }
    ?>

    <div class="container">
        <form action="" method="POST" class="form">
            <h1>Recuperar a Senha</h1>
            <label for="Email">Email</label>
            <input type="text" name="usuario" placeholder="Digite seu email"><br>
            <input type="submit" name="SendRecupSenha" value="Recuperar">
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