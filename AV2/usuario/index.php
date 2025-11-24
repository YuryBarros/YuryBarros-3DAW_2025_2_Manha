<?php
session_start();
require_once __DIR__ . '/../db_connect.php'; // Conecta ao banco
include 'header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-section {
        position: relative;
        width: 100%;
        height: 550px;
        background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.2)), url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=1920&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        margin-bottom: 50px;
    }
    
    .hero-content h1 {
        font-size: 56px;
        font-weight: 800;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }

    .hero-content p {
        font-size: 20px;
        margin-bottom: 40px;
        max-width: 700px;
        opacity: 0.9;
    }

    .btn-hero {
        background-color: var(--accent) !important;
        color: white !important;
        padding: 15px 45px;
        font-size: 18px;
        font-weight: 700;
        text-decoration: none;
        border-radius: 50px;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(76, 175, 80, 0.4);
    }
    .btn-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(76, 175, 80, 0.6);
    }

    .section-title {
        text-align: center;
        color: var(--dark);
        margin-bottom: 50px;
        font-size: 36px;
        text-transform: uppercase;
        font-weight: 800;
        letter-spacing: -1px;
    }

    .swiper {
        width: 100%;
        max-width: 1300px;
        padding-bottom: 60px !important;
        padding-left: 20px;
        padding-right: 20px;
    }

    .car-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        transition: 0.3s;
        width: 300px;
        text-align: center;
        padding-bottom: 30px;
        border: 1px solid #f0f0f0;
    }
    
    .car-img-box {
        height: 190px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        padding: 10px;
    }
    .car-img-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1));
    }
    .car-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 5px;
    }
    .car-price {
        font-size: 26px;
        color: var(--primary);
        font-weight: 800;
        margin-bottom: 20px;
    }

    .car-btn {
        background: var(--primary);
        color: white;
        padding: 12px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: 0.3s;
        text-transform: uppercase;
        font-size: 14px;
    }
    .car-btn:hover {
        background: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .swiper-button-next, .swiper-button-prev {
        color: var(--primary) !important;
        background: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .swiper-button-next::after, .swiper-button-prev::after { font-size: 20px; font-weight: bold; }

    .benefits-section {
        background: white;
        margin-top: 100px;
        padding: 80px 20px;
    }
    .benefits-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 50px;
        text-align: center;
    }
    .benefit-item i {
        font-size: 32px;
        color: var(--accent);
        margin-bottom: 20px;
        background: #ecfdf5;
        width: 90px;
        height: 90px;
        line-height: 90px;
        border-radius: 50%;
    }
    .benefit-item h4 { margin-bottom: 10px; font-size: 18px; font-weight: 700; }
    .benefit-item p { color: #64748b; font-size: 15px; line-height: 1.6; }
</style>

<div class="hero-section">
    <div class="hero-content">
        <h1>Liberdade para ir além</h1>
        <p>Os melhores carros, as melhores taxas e a segurança que você precisa para sua viagem.</p>
        <a href="carros.php" class="btn-hero">Ver Frota Completa</a>
    </div>
</div>

<h2 class="section-title">Mais Alugados</h2>

<div class="swiper mySwiper">
    <div class="swiper-wrapper">
        
        <?php
        try {
            // Busca os carros no banco
            $sql = "SELECT * FROM carros_fallscar WHERE destaque_home = 1";
            $stmt = $conn->query($sql);
            
            while ($carro = $stmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="swiper-slide">
                    <div class="car-card">
                        <div class="car-img-box">
                            <img src="<?php echo $carro['imagem_url']; ?>" alt="Carro" onerror="this.src='https://cdn-icons-png.flaticon.com/512/744/744465.png'; this.style.opacity='0.5';">
                        </div>
                        <div class="car-title"><?php echo $carro['modelo']; ?></div>
                        <p style="font-size: 12px; color: #888; margin-bottom: 5px;">A partir de</p>
                        <div class="car-price">R$ <?php echo number_format($carro['preco_diaria'], 2, ',', '.'); ?> <span style="font-size: 12px; color: #666; font-weight: normal;">/dia</span></div>
                        <a href="detalhes_carro.php?id=<?php echo $carro['id']; ?>" class="car-btn">RESERVAR</a>
                    </div>
                </div>
                <?php
            }
        } catch (PDOException $e) {
            echo "<p>Erro ao carregar carros: " . $e->getMessage() . "</p>";
        }
        ?>

    </div>
    
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>


<div class="benefits-section">
    <div class="benefits-grid">
        <div class="benefit-item">
            <i class="fa fa-ticket-alt"></i>
            <h4>Desconto de Primeira</h4>
            <p>Use <strong>fallscar10</strong> e ganhe<br>10% off no primeiro aluguel.</p>
        </div>
        <div class="benefit-item">
            <i class="fa fa-check-circle"></i>
            <h4>Revisão Garantida</h4>
            <p>Carros inspecionados semanalmente<br>para sua segurança.</p>
        </div>
        <div class="benefit-item">
            <i class="fa fa-user-tie"></i>
            <h4>Suporte 24h</h4>
            <p>Atendimento humanizado<br>sempre que precisar.</p>
        </div>
        <div class="benefit-item">
            <i class="fa fa-credit-card"></i>
            <h4>Facilidade no Pagamento</h4>
            <p>Parcele em até 5x sem juros<br>no cartão de crédito.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
            1300: {
                slidesPerView: 4,
            },
        },
    });
</script>

<?php include 'footer.php'; ?>
