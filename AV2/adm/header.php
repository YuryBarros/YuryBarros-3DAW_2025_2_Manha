<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Falls Car - Aluguel Premium</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header class="main-header">
    <div class="header-container">
        
        <a href="../usuario/index.php" class="brand-logo">
            <i class="fa fa-car-side"></i> FALLS CAR
        </a>

        <form action="../usuario/carros.php" method="GET" class="search-compact">
            
            <div class="search-group">
                <i class="fa fa-map-marker-alt"></i>
                <input type="text" name="local_retirada" placeholder="Onde vai retirar?" class="clean-input" required>
            </div>
            
            <div class="divider"></div>
            
            <div class="search-group">
                <i class="fa fa-calendar-alt"></i>
                <input type="text" onfocus="(this.type='date')" onblur="(this.type='text')" placeholder="Data de retirada" name="data_retirada" class="clean-input date-input">
            </div>

            <button type="submit" class="btn-search-icon">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <div class="nav-area">
            <nav class="nav-links">
                <a href="../usuario/carros.php">Frota</a>
                <a href="../usuario/duvidas.php">Ajuda</a>
                <a href="../usuario/contato.php">Contato</a>
            </nav>
            
            <div class="user-actions">
                <?php if(isset($_SESSION['usuario_logado'])): ?>
                    <div class="dropdown">
                        <a href="../usuario/perfil.php" class="user-btn logged">
                            <i class="fa fa-user-circle"></i>
                            <span>Olá, <?php echo explode(' ', $_SESSION['usuario_nome'])[0]; ?></span>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="../usuario/login.php" class="user-btn">
                        <i class="fa fa-user"></i> Entrar
                    </a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</header>

<main>
