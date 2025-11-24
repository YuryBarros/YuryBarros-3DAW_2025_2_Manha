<?php
session_start();
if(isset($_SESSION['usuario_logado'])){
    header("Location: index.php");
    exit;
}
include 'header.php';
?>

<style>
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 60vh;
        padding: 20px;
    }
    .login-card {
        background-color: white;
        padding: 40px;
        border-radius: 15px;
        width: 100%;
        max-width: 400px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }
    .input-group { margin-bottom: 15px; }
    .input-group input {
        width: 100%; padding: 12px; border-radius: 5px; border: 1px solid #ccc;
    }
    .btn-entrar {
        background-color: var(--primary-green); color: white;
        padding: 12px; border: none; border-radius: 5px;
        width: 100%; cursor: pointer; font-size: 16px; font-weight: bold;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h2 style="color: var(--primary-green); margin-bottom: 20px;">Login</h2>
        
        <?php if(isset($_GET['erro'])): ?>
            <p style="color: red; font-size: 14px;">E-mail ou senha incorretos.</p>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <div class="input-group">
                <input type="email" name="email" placeholder="E-mail" required>
            </div>
            <div class="input-group">
                <input type="password" name="senha" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn-entrar">Entrar</button>
        </form>
        
        <p style="margin-top: 15px; font-size: 14px;">
            Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
