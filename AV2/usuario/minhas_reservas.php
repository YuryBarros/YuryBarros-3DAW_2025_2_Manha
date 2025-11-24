<?php
session_start();
require_once __DIR__ . '/../db_connect.php';
include 'header.php';

// Se não estiver logado, manda pro login
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit;
}

$id_cliente = $_SESSION['usuario_id'];

try {
    // Busca as reservas desse cliente E os dados do carro associado
    // Usamos JOIN para juntar as tabelas 'reservas' e 'carros_fallscar'
    $sql = "SELECT r.*, c.modelo, c.imagem_url 
            FROM reservas r 
            JOIN carros_fallscar c ON r.id_carro = c.id 
            WHERE r.id_cliente = :id 
            ORDER BY r.data_reserva DESC";
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_cliente);
    $stmt->execute();
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
?>

<style>
    .page-header-box {
        background-color: #dcdcdc;
        padding: 20px;
        text-align: center;
        border-radius: 50px;
        width: fit-content;
        margin: 30px auto;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .page-header-box h2 { margin: 0; color: #333; }
    .page-header-box i { font-size: 30px; color: #333; }

    /* Card de Reserva */
    .reserva-card {
        display: flex;
        flex-wrap: wrap;
        border: 1px solid #333;
        margin: 20px auto;
        max-width: 900px;
        background: white;
    }
    .reserva-img {
        width: 250px;
        object-fit: cover;
        border-right: 1px solid #333;
    }
    .reserva-info {
        flex: 1;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .info-column { font-size: 14px; line-height: 1.6; color: #333; }
    .status-badge {
        padding: 8px 20px;
        border-radius: 20px;
        font-weight: bold;
        text-align: center;
        border: 2px solid #000;
        display: inline-block;
    }
    .status-finalizada { background-color: #4caf50; color: #000; }
    .status-andamento { background-color: #ffff00; color: #000; }
</style>

<div class="page-header-box">
    <h2>Reservas</h2>
    <i class="fa fa-key"></i>
</div>

<?php if(isset($_GET['sucesso'])): ?>
    <div style="text-align:center; background: #d4edda; color: #155724; padding: 10px; margin: 10px auto; max-width: 900px; border-radius: 5px;">
        Pagamento confirmado! Sua reserva foi realizada.
    </div>
<?php endif; ?>

<div style="padding: 20px;">
    
    <?php if (count($reservas) > 0): ?>
        
        <?php foreach($reservas as $reserva): 
            // Calcula dias alugados
            $data1 = new DateTime($reserva['data_retirada']);
            $data2 = new DateTime($reserva['data_entrega']);
            $intervalo = $data1->diff($data2);
            $dias = $intervalo->days;
        ?>
            <div class="reserva-card">
                <img src="<?php echo $reserva['imagem_url']; ?>" alt="Carro" class="reserva-img"
                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/744/744465.png';">
                
                <div class="reserva-info">
                    <div class="info-column">
                        <strong>Local de retirada:</strong> Agência Central<br>
                        <strong>Data de retirada:</strong> <?php echo date('d/m/Y', strtotime($reserva['data_retirada'])); ?><br>
                        <strong>Motorista:</strong> Sim
                    </div>

                    <div class="info-column">
                        <strong>Local de entrega:</strong> Agência Central<br>
                        <strong>Data de entrega:</strong> <?php echo date('d/m/Y', strtotime($reserva['data_entrega'])); ?><br>
                        <strong>Dias alugados:</strong> <?php echo $dias; ?> dias.
                    </div>

                    <div style="text-align: center;">
                        <p style="margin-bottom: 5px;">Status da reserva</p>
                        <span class="status-badge status-finalizada">
                            <?php echo $reserva['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <p style="text-align: center; color: #666;">Você ainda não possui reservas.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="background: var(--primary-green); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Alugar um carro agora</a>
        </div>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>
