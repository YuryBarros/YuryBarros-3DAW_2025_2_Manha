<?php
require_once __DIR__ . '/../db_connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO clientes (nome, email, cpf, senha) VALUES (:nome, :email, :cpf, :senha)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();
        header("Location: login.php?sucesso=1");
    } catch (PDOException $e) { echo "Erro: " . $e->getMessage(); }
}
?>
