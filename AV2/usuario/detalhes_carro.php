<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Valida ID do carro
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

try {
    $sql = "SELECT * FROM carros_fallscar WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $carro = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carro) {
        header("Location: index.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
    die();
}

include 'header.php';
?>

<style>
    .details-container {
        max-width: 1100px;
        margin: 50px auto;
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        display: flex;
        flex-wrap: wrap;
        overflow: hidden;
    }

    .details-img {
        flex: 1.5;
        min-width: 400px;
        background-color: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        border-right: 1px solid #f1f5f9;
    }
    
    .details-img img {
        width: 100%;
        max-width: 500px;
        object-fit: contain;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.15));
        transition: transform 0.3s;
    }
    .details-img img:hover {
        transform: scale(1.05);
    }

    .details-info {
        flex: 1;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    h1 {
        font-size: 36px;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 10px;
        text-transform: uppercase;
        line-height: 1.2;
    }

    .car-specs {
        background: #f1f5f9;
        padding: 25px;
        border-radius: 12px;
        margin: 20px 0;
    }

    .car-specs h4 {
        margin: 0 0 15px 0;
        color: var(--primary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 1px;
    }

    .car-specs ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .car-specs li {
        margin-bottom: 8px;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .car-specs li::before {
        content: '✔';
        color: var(--accent);
        font-weight: bold;
    }

    .price-tag {
        font-size: 42px;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 5px;
    }
    .price-tag span {
        font-size: 16px;
        font-weight: 500;
        color: #94a3b8;
    }

    .installments {
        color: var(--accent);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 30px;
        display: block;
    }

    .btn-alugar-grande {
        background-color: var(--primary) !important;
        color: white !important;
        text-decoration: none;
        display: block;
        text-align: center;
        padding: 18px;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(14, 95, 34, 0.3);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-alugar-grande:hover {
        background-color: var(--accent) !important;
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(76, 175, 80, 0.4);
    }

    @media (max-width: 768px) {
        .details-img { border-right: none; border-bottom: 1px solid #eee; padding: 20px; }
        h1 { font-size: 28px; }
        .details-container { margin: 20px; }
    }
</style>

<div class="details-container">
    
    <div class="details-img">
        <img src="<?php echo $carro['imagem_url']; ?>" alt="<?php echo $carro['modelo']; ?>" 
             onerror="this.src='https://cdn-icons-png.flaticon.com/512/744/744465.png';">
    </div>

    <div class="details-info">
        <h1><?php echo $carro['modelo']; ?></h1>
        <p style="color: #64748b; font-size: 14px;">Categoria Premium • Disponível na Agência Central</p>

        <div class="car-specs">
            <h4>Destaques do Veículo:</h4>
            <ul>
                <li>Marca: <strong><?php echo $carro['marca']; ?></strong></li>
                <?php 
                $descricoes = explode("\n", $carro['descricao']);
                foreach($descricoes as $desc) {
                    if(trim($desc)) echo "<li>" . trim($desc) . "</li>";
                }
                ?>
            </ul>
        </div>

        <div>
            <div class="price-tag">
                R$ <?php echo number_format($carro['preco_diaria'], 2, ',', '.'); ?>
                <span>/ dia</span>
            </div>
            <span class="installments"><i class="fa fa-credit-card"></i> Em até 10x sem juros</span>

            <a href="pagamento.php?id_carro=<?php echo $carro['id']; ?>" class="btn-alugar-grande">
                ALUGAR AGORA <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>

</div>

<?php include 'footer.php'; ?>
