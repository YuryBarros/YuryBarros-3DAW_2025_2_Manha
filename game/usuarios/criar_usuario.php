<?php

$arquivo = "usuarios.txt";

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);

    // Validações simples
    if ($nome != "" && $email != "") {
        // Gera um ID único com base no timestamp
        $id = time();
        $linha = $id . ";" . $nome . ";" . $email . PHP_EOL;

        // Salva no arquivo
        file_put_contents($arquivo, $linha, FILE_APPEND);

        echo "<p>Usuário cadastrado com sucesso!</p>";
        echo '<a href="listar_usuarios.php">Ver lista de usuários</a>';
        exit;
    } else {
        echo "<p>Preencha todos os campos!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h2>Cadastrar Usuário</h2>
    <form method="post">
        Nome: <input type="text" name="nome" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="/game/usuarios/listar_usuarios.php">Ver lista de usuários</a>
    <a href="/game/index.php">Voltar ao menu</a>
</body>
</html>