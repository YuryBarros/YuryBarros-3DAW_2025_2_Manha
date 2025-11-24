<?php
session_start();
require_once __DIR__ . '/../db_connect.php';
include 'header.php';

// Parâmetros de busca
$local = $_GET['local_retirada'] ?? '';
$data = $_GET['data_retirada'] ?? '';

$filtro_sql = "";
$parametros = [];
$filtro_sql = "";
$parametros = [];

if($local) {
    $filtro_sql = " WHERE modelo LIKE :busca OR marca LIKE :busca OR descricao LIKE :busca";
    $parametros = [':busca' => "%$local%"];
}
?>

<style>
    .results-header {
        text-align: center;
        padding: 40px 20px;
        background: white;
        margin-bottom: 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .results-header h2 { color: var(--dark); font-weight: 800; font-size: 28px; }
    .results-header span { color: var(--primary); text-decoration: underline; }
    .search-tag {
        display: inline-block;
        background: #f1f5f9;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 13px;
        color: #64748b;
        margin-top: 10px;
    }

    .cars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto 60px auto;
        padding: 0 20px;
    }

    .listing-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #eee;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .listing-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: var(--accent); }

    .listing-img {
        height: 180px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }
    .listing-img img { max-width: 100%; max-height: 100%; object-fit: contain; }

    .listing-content { padding: 20px; text-align: center; display: flex; flex-direction: column; flex-grow: 1; }
    .listing-title { font-size: 18px; font-weight: 700; color: var(--dark); margin-bottom: 10px; text-transform: uppercase; }
    .listing-desc { font-size: 13px; color: #64748b; margin-bottom: 15px; line-height: 1.5; flex-grow: 1; }
    .listing-price { font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 20px; }

    .btn-rent {
        background-color: var(--primary);
        color: white;
        text-decoration: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        display: block;
        width: 100%;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 14px;
        border: none;
    }
    .btn-rent:hover { background-color: var(--accent); transform: scale(1.02); }

    .no-results {
        grid-column: 1 / -1;
        text-align: center;
        padding: 50px;
        background: white;
        border-radius: 10px;
        color: #666;
    }
</style>

<div class="results-header">
    <?php if($local): ?>
        <h2>Resultados para: <span>"<?php echo htmlspecialchars($local); ?>"</span></h2>
        
        <?php if($data): ?>
            <div class="search-tag"><i class="fa fa-calendar-alt"></i> Data: <?php echo date('d/m/Y', strtotime($data)); ?></div>
        <?php endif; ?>

        <div style="margin-top: 15px;">
            <a href="carros.php" style="color: #666; font-size: 14px;">Limpar filtros</a>
        </div>

    <?php else: ?>
        <h2>Nossa Frota Completa</h2>
        <p style="color: #666; margin-top: 5px;">Escolha o carro ideal para sua próxima viagem</p>
    <?php endif; ?>
</div>

<div class="cars-grid">
    <?php
    try {
        $sql = "SELECT * FROM carros_fallscar" . $filtro_sql;
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametros);
        
        if($stmt->rowCount() > 0) {
            while ($carro = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="listing-card">
                    <div class="listing-img">
                        <img src="<?php echo $carro['imagem_url']; ?>" alt="<?php echo $carro['modelo']; ?>" 
                             onerror="this.src='https://cdn-icons-png.flaticon.com/512/744/744465.png'; this.style.opacity='0.5';">
                    </div>
                    <div class="listing-content">
                        <h3 class="listing-title"><?php echo $carro['modelo']; ?></h3>
                        <p class="listing-desc">
                           <?php echo substr($carro['descricao'], 0, 80) . (strlen($carro['descricao']) > 80 ? '...' : ''); ?>
                        </p>
                        <div class="listing-price">
                            R$ <?php echo number_format($carro['preco_diaria'], 2, ',', '.'); ?> <small style="font-size: 12px; color: #999; font-weight: normal;">/dia</small>
                        </div>
                        <a href="detalhes_carro.php?id=<?php echo $carro['id']; ?>" style="text-decoration: none;">
                            <button class="btn-rent">
                                <i class="fa fa-key"></i> Reservar Agora
                            </button>
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="no-results">
                <i class="fa fa-search" style="font-size: 50px; color: #ccc; margin-bottom: 20px;"></i>
                <h3>Nenhum carro encontrado nesta localização.</h3>
                <p>Tente buscar por "Argo", "Jeep" ou "Toyota".</p>
                <a href="carros.php" class="btn-rent" style="width: 200px; margin: 20px auto;">Ver Todos</a>
            </div>
            <?php
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
    ?>
</div>

<?php include 'footer.php'; ?>
