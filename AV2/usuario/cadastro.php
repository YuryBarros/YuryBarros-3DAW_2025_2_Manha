<?php include 'header.php'; ?>

<div style="display:flex; justify-content:center; padding:50px;">
    <div style="background:white; padding:30px; border-radius:10px; border:1px solid #ddd; width:100%; max-width:400px;">
        <h2 style="text-align:center; color:var(--primary-green); margin-bottom:20px;">Cadastro</h2>
        <form action="cadastro_process.php" method="POST">
            <input type="text" name="nome" placeholder="Nome" required style="width:100%; padding:10px; margin-bottom:10px;">
            <input type="email" name="email" placeholder="E-mail" required style="width:100%; padding:10px; margin-bottom:10px;">
            <input type="text" name="cpf" placeholder="CPF" required style="width:100%; padding:10px; margin-bottom:10px;">
            <input type="password" name="senha" placeholder="Senha" required style="width:100%; padding:10px; margin-bottom:20px;">
            <button type="submit" style="width:100%; padding:10px; background:var(--primary-green); color:white; border:none; cursor:pointer;">Cadastrar</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
