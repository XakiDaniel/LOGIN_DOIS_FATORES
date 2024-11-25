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
</head>
<body>
    
    <div class="main-content">
        <header>
            <h1>Bem-vindo, <?= $_SESSION['nome']; ?>!</h1>
            <div class="header-btns">
                <button><i class="fas fa-plus"></i> Nova Função</button>
                <button><i class="fas fa-cog"></i> Configurações</button>
            </div>
        </header>
        <a href="sair.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>

        <div class="content">
            <div class="section">
                <h3>Área para desenvolvimento</h3>
                <p>Você pode adicionar novos recursos aqui conforme for desenvolvendo.</p>
            </div>
        </div>
    </div>
</body>
</html>
