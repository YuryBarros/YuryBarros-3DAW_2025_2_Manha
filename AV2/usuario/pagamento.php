<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Validações iniciais
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit;
}

// Verifica se tem carro selecionado
if (!isset($_GET['id_carro'])) {
    header("Location: index.php");
    exit;
}

$id_carro = $_GET['id_carro'];

// Busca dados do carro no banco
$stmt = $conn->prepare("SELECT * FROM carros_fallscar WHERE id = :id");
$stmt->bindParam(':id', $id_carro);
$stmt->execute();
$carro = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o carro não existir, volta pra home
if(!$carro) {
    header("Location: index.php");
    exit;
}

// Cálculos de simulação
$data_retirada = date('Y-m-d');
$data_entrega = date('Y-m-d', strtotime('+5 days'));
$dias = 5;
$total = $carro['preco_diaria'] * $dias;

// --- SÓ AGORA QUE TUDO DEU CERTO, CARREGAMOS O HEADER (VISUAL) ---
include 'header.php';
?>

<style>
    .checkout-container {
        display: flex;
        flex-wrap: wrap;
        max-width: 1100px;
        margin: 50px auto;
        gap: 50px;
        padding: 0 20px;
    }
    
    .form-section {
        flex: 2;
        min-width: 350px;
    }
    
    h2 {
        color: var(--primary);
        font-weight: 800;
        margin-bottom: 30px;
        font-size: 28px;
        border-left: 5px solid var(--accent);
        padding-left: 15px;
    }

    .form-group { margin-bottom: 20px; }
    .form-group label { 
        display: block; 
        color: var(--dark); 
        font-weight: 600; 
        margin-bottom: 8px; 
        font-size: 14px;
    }
    .form-group input, .form-group select {
        width: 100%; 
        padding: 12px 15px; 
        border: 1px solid #cbd5e1; 
        border-radius: 8px;
        background: white;
        font-size: 15px;
        transition: 0.3s;
    }
    .form-group input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(14, 95, 34, 0.1);
    }
    
    .row { display: flex; gap: 20px; }
    
    .summary-section {
        flex: 1;
        min-width: 300px;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: fit-content;
        border: 1px solid #f1f5f9;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
        font-size: 14px;
        color: #64748b;
    }
    .summary-row strong { color: var(--dark); }

    .total-box {
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        border: 1px dashed #cbd5e1;
    }

    .btn-finalizar {
        background-color: var(--primary);
        color: white;
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        transition: 0.3s;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-finalizar:hover { 
        background-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
</style>

<div class="checkout-container">
    <div class="form-section">
        <h2 style="color: var(--primary-green); text-align: center; margin-bottom: 20px; text-transform: uppercase;">Efetuar Pagamento</h2>
        
        <form action="processar_reserva.php" method="POST">
            <input type="hidden" name="id_carro" value="<?php echo $carro['id']; ?>">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <input type="hidden" name="data_retirada" value="<?php echo $data_retirada; ?>">
            <input type="hidden" name="data_entrega" value="<?php echo $data_entrega; ?>">

            <h4 style="color: #666; margin-bottom: 15px;">Dados do titular do cartão</h4>
            
            <div class="form-group">
                <label>Número do cartão</label>
                <div style="position: relative;">
                    <input type="text" placeholder="0000 0000 0000 0000" required>
                    <i class="fab fa-cc-visa" style="position: absolute; right: 10px; top: 10px; font-size: 20px; color: #1a1f71;"></i>
                </div>
            </div>

            <div class="row">
                <div class="form-group" style="flex: 1;">
                    <label>Validade</label>
                    <input type="text" placeholder="MM/AA" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>CVV</label>
                    <input type="text" placeholder="123" required>
                </div>
            </div>

            <div class="form-group">
                <label>Nome completo</label>
                <input type="text" value="<?php echo $_SESSION['usuario_nome']; ?>" required>
            </div>

            <div class="form-group">
                <label>CPF</label>
                <input type="text" placeholder="000.000.000-00" required>
            </div>

            <div class="row">
                <div class="form-group" style="flex: 1;">
                    <label>Telefone</label>
                    <input type="text" placeholder="(21) 99999-9999">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Data de nascimento</label>
                    <input type="date">
                </div>
            </div>

            <div class="form-group">
                <label>Parcelas</label>
                <select>
                    <option>1x de R$ <?php echo $total; ?> sem juros</option>
                    <option>2x de R$ <?php echo $total/2; ?> sem juros</option>
                    <option>3x de R$ <?php echo $total/3; ?> sem juros</option>
                </select>
            </div>

            <button type="submit" class="btn-finalizar">
                Finalizar compra <i class="fa fa-lock"></i>
            </button>
        </form>
    </div>

    <div class="summary-section">
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">Resumo do pedido</h3>
        
        <p style="color: #999; font-size: 12px;">Itens do pedido</p>
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <strong><?php echo $carro['modelo']; ?></strong>
            <span>R$ <?php echo number_format($carro['preco_diaria'], 2, ',', '.'); ?></span>
        </div>

        <p style="color: #999; font-size: 12px;">Período</p>
        <p style="font-size: 14px; margin-bottom: 20px;">
            <?php echo date('d/m', strtotime($data_retirada)); ?> até <?php echo date('d/m', strtotime($data_entrega)); ?> 
            (<?php echo $dias; ?> dias)
        </p>

        <p style="color: #999; font-size: 12px;">Forma de pagamento</p>
        <p style="font-size: 14px; margin-bottom: 20px;"><i class="far fa-credit-card"></i> Cartão de Crédito</p>

        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #999;">Total a pagar:</span>
                <span style="font-size: 24px; font-weight: bold;">R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
