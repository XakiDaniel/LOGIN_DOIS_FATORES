<?php
    session_start();

    if((!isset($_SESSION['id'])) and (!isset($_SESSION['usuario'])) and (!isset($_SESSION['codigo_autenticacao']))) {
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Você precisa está logado para acessar o sistema</p>";
        header("Location: index.php");
        exit;
    }
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
?>