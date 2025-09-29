<?php
$arquivo = "usuarios.txt";
$usuarios = [];

// Lê o arquivo, se existir
if (file_exists($arquivo)) {
    $usuarios = file($arquivo, FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { border-collapse: collapse; width: 80%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: #2980b9; }
        a:hover { color: #e67e22; }
    </style>
</head>
<body>
    <h2>Usuários Cadastrados</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php if (count($usuarios) > 0): ?>
            <?php foreach ($usuarios as $linha): ?>
                <?php list($id, $nome, $email) = explode(";", $linha); ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $nome ?></td>
                    <td><?= $email ?></td>
                    <td>
                        <a href="/game/usuarios/editar_usuario.php?id=<?= $id ?>">Editar</a> |
                        <a href="/game/usuarios/excluir_usuario.php?id=<?= $id ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nenhum usuário cadastrado.</td></tr>
        <?php endif; ?>
    </table>
    <br>
    <a href="/game/usuarios/criar_usuario.php">+ Criar novo usuário</a><br>
    <a href="/game/index.php">Voltar ao menu</a>
</body>
</html>