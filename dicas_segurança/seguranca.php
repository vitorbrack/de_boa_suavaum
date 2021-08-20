<?php
/*
 código de segurança aplicado na página de validação do login
 verifia se a requisição da página foi através de formulário e pelo método post,
 e se utilizou as variáveis esperadas */
if (!empty($_POST) AND !isset($_POST) AND (empty($_POST['login']) 
	OR empty($_POST['senha']))) {
	//destroi as sessões e redireciona para a página inicial.
	header("Location: ../sessionDestroy.php"); exit;
}


/*
 código de segurança aplicado nas páginas que se deseja assegurar.
*/
session_start();

if((!isset($_SESSION['loginp']) || !isset($_SESSION['nomep'])) ||
    !isset($_SESSION['perfilp']) || !isset($_SESSION['nr']) ||
    $_SESSION['nr'] < 1 || ($_SESSION['nr'] != $_SESSION['confereNr'])) { 
    //Usuário não logado! Redireciona para a página de login 
    header("Location: sessionDestroy.php");
    exit;
}


/*
 código que derruba o login.
 Aplicado num arquivo a fim de destruir as sessões e encaminhar
 para a página de login
*/
ob_start();
session_start();
session_destroy();
header("Location: index.php");
exit;
ob_end_flush();