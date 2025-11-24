<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Verifica se veio dados do formulário (POST) e se o usuário está logado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['usuario_logado'])) {
    
    $id_cliente = $_SESSION['usuario_id'];
    $id_carro = $_POST['id_carro'];
    $data_retirada = $_POST['data_retirada'];
    $data_entrega = $_POST['data_entrega'];
    $total = $_POST['total'];

    try {
        // Insere a reserva
        $sql = "INSERT INTO reservas (id_cliente, id_carro, data_retirada, data_entrega, valor_total, status) 
                VALUES (:cliente, :carro, :retirada, :entrega, :total, 'Finalizada')";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':cliente', $id_cliente);
        $stmt->bindParam(':carro', $id_carro);
        $stmt->bindParam(':retirada', $data_retirada);
        $stmt->bindParam(':entrega', $data_entrega);
        $stmt->bindParam(':total', $total);
        
        $stmt->execute();

        // Redireciona para a página de Minhas Reservas
        header("Location: minhas_reservas.php?sucesso=1");
        exit;

    } catch (PDOException $e) {
        echo "Erro ao reservar: " . $e->getMessage();
    }
} else {
    // Se tentar entrar direto na página sem enviar formulário, manda pra home
    header("Location: index.php");
    exit;
}
?>
