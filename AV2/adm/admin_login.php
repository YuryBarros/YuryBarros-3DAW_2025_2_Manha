<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Se já estiver logado, manda direto pro painel
if (isset($_SESSION['admin_logado'])) {
    header("Location: admin_dashboard.php");
    exit;
}

// Processa login do admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        // Busca o admin no banco
        $stmt = $conn->prepare("SELECT * FROM usuarios_admin WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha
        // NOTA: se as senhas não estiverem com hash no DB, ajuste a verificação conforme necessário
        if ($admin && $senha == $admin['senha']) {
            
            $_SESSION['admin_logado'] = true;
            $_SESSION['admin_nome'] = $admin['nome'];
            $_SESSION['admin_id'] = $admin['id'];
            
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $erro = "E-mail ou senha inválidos!";
        }
    } catch (PDOException $e) {
        $erro = "Erro no banco: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Falls Car</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #2c3e50; /* Fundo escuro para diferenciar do site do cliente */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            width: 100%;
            max-width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .login-box h2 { color: #333; margin-bottom: 20px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #1f6391; }
        .erro { color: red; font-size: 14px; margin-bottom: 15px; }
        .btn-voltar { display: block; margin-top: 15px; color: #666; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Área Restrita</h2>
    
    <?php if(isset($erro)): ?>
        <p class="erro"><?php echo $erro; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="E-mail Admin" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">ENTRAR</button>
    </form>

    <a href="index.php" class="btn-voltar">← Voltar ao Site</a>
</div>

</body>
</html>
