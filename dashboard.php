<?php 
    session_start();
    ob_start(); # Limpando o buffer de saida
    

	require_once 'config.php';

    if((!isset($_SESSION['id'])) and (!isset($_SESSION['usuario'])) and (!isset($_SESSION['codigo_autenticacao']))) {
        $_SESSION['msg'] = "<p class='error'>Erro: Você precisa está logado para acessar o sistema</p>";
        header("Location: index.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="./assests/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <!-- <p class="user-name">Bem-vindo, !</p>
    <div class="main-content">
    <li><a href="#">Contato</a></li> -->
    
    <!-- Load an icon library to show a hamburger menu (bars) on small screens -->

<!-- Top Navigation Menu -->
    <div class="topnav">
        <a href="#home" class="active">Logo</a>
        <!-- Navigation links (hidden by default) -->
        <div id="myLinks">
            <a href="#news">Home</a>
            <a href="#contact">Sobre</a>
            <a href="sair.php">Sair</a>
            <a href="">Bem vindo,<?= $_SESSION['nome']; ?></a>
        </div>
        <!-- "Hamburger menu" / "Bar icon" to toggle the navigation links -->
        <a href="javascript:void(0);" class="icon" onclick="myFunction()">
            <i class="fa fa-bars"></i>
        </a>
    </div>
    <script src="./assests/js/main.js"></script>
</body>
</html>
