<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Falls Car Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #0e5f22; /* Verde da marca */
            --dark: #1e293b;
            --light: #f1f5f9;
            --white: #ffffff;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            margin: 0;
            color: var(--dark);
        }

        /* Header */
        .navbar {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            font-size: 20px;
            color: var(--primary);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
        }

        .btn-logout {
            padding: 8px 20px;
            border-radius: 30px;
            text-decoration: none;
            color: #ef4444;
            border: 1px solid #ef4444;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-logout:hover {
            background-color: #ef4444;
            color: white;
        }

        /* Grid */
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        .welcome-text {
            margin-bottom: 40px;
        }
        .welcome-text h1 { font-size: 28px; margin: 0; }
        .welcome-text p { color: #64748b; margin-top: 5px; }

        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .card {
            background: var(--white);
            padding: 40px;
            border-radius: 16px;
            text-decoration: none;
            color: var(--dark);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: var(--primary);
        }

        .icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
        }

        /* Cores para cards */
        .card-carros .icon-box { background-color: #dcfce7; color: #16a34a; }
        .card-reservas .icon-box { background-color: #e0f2fe; color: #0284c7; }
        .card-site .icon-box { background-color: #f3e8ff; color: #9333ea; }

        .card h3 { margin: 0 0 10px 0; font-size: 20px; }
        .card p { color: #64748b; font-size: 14px; line-height: 1.5; }

    </style>
</head>
<body>

<nav class="navbar">
    <div class="brand">
        <i class="fa fa-shield-alt"></i> GESTÃO FALLS CAR
    </div>
    <div class="user-menu">
        <span>Olá, <strong><?php echo $_SESSION['admin_nome']; ?></strong></span>
        <a href="admin_logout.php" class="btn-logout">Sair <i class="fa fa-sign-out-alt"></i></a>
    </div>
</nav>

<div class="container">
    
    <div class="welcome-text">
        <h1>Painel de Controle</h1>
        <p>Gerencie sua frota e acompanhe reservas em tempo real.</p>
    </div>

    <div class="grid-menu">
        <a href="admin_carros.php" class="card card-carros">
            <div class="icon-box"><i class="fa fa-car"></i></div>
            <h3>Frota de Veículos</h3>
            <p>Cadastre novos carros, edite preços, imagens e remova veículos indisponíveis.</p>
        </a>

        <a href="admin_reservas.php" class="card card-reservas">
            <div class="icon-box"><i class="fa fa-clipboard-list"></i></div>
            <h3>Controle de Reservas</h3>
            <p>Visualize todos os pedidos, datas de retirada/entrega e status financeiro.</p>
        </a>

        <a href="index.php" target="_blank" class="card card-site">
            <div class="icon-box"><i class="fa fa-external-link-alt"></i></div>
            <h3>Acessar Site</h3>
            <p>Visualize a loja como um cliente comum em uma nova aba.</p>
        </a>
    </div>

</div>

</body>
</html>
