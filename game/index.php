<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Jogo Corporativo - Menu Principal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        header {
            background: #2c3e50;
            color: white;
            padding: 20px 40px;
            text-align: center;
        }
        h1 { margin: 0; font-size: 2em; }
        .menu {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 40px;
        }
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 250px;
            margin: 15px;
            padding: 20px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        .card a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }
        .card a:hover { color: #e67e22; }
        footer {
            text-align: center;
            margin: 50px 0 20px 0;
            color: #555;
        }
        .icon {
            font-size: 40px;
            margin-bottom: 10px;
            display: block;
        }
    </style>
</head>
<body>

<header>
    <h1>Sistema de Treinamento Corporativo</h1>
    <p>Treine gestores com decisões e desafios corporativos</p>
</header>

<div class="menu">
    <!-- Bloco de Usuários -->
    <div class="card">
        <span class="icon">👤</span>
        <h3>Usuários</h3>
        <a href="usuarios/criar_usuario.php">➕ Criar Usuário</a>
        <a href="usuarios/listar_usuarios.php">📋 Listar Usuários</a>
    </div>

    <!-- Bloco de Perguntas -->
    <div class="card">
        <span class="icon">❓</span>
        <h3>Perguntas</h3>
        <a href="perguntas/criar_pergunta.php">➕ Criar Pergunta</a>
        <a href="perguntas/listar_perguntas.php">📋 Listar Perguntas</a>
    </div>
</div>

<footer>
    <small>Desenvolvido para treinamento de gestores</small>
</footer>

</body>
</html>