<?php
//exemplo de referência por parâmetro com apelido para as variáveis
$nome = 'Bill Gates';
$site = 'http://microsoft.com';
$stmt = $PDO->prepare("INSERT INTO programadores(nome, site) VALUES(:a, :b)");
$stmt->bindParam( ':a', $nome );
$stmt->bindParam( ':b', $site );
 
$result = $stmt->execute();
 
if ( ! $result )
{
    var_dump( $stmt->errorInfo() );
    exit;
}
 
echo $stmt->rowCount() . "linhas inseridas";
?>