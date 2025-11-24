<?php
// Arquivo: db_connect.php

$host = 'localhost';
$db_name = 'fallscar'; // O nome do seu BANCO DE DADOS no phpMyAdmin
$username = 'root';    // Usuário padrão do XAMPP
$password = '';        // Senha padrão do XAMPP (vazia)

try {
    // Cria a conexão usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
    // Configura o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Se der erro, mostra na tela
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    die();
}
?>