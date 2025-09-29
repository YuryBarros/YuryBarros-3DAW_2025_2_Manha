<?php

$arquivo = "usuarios.txt";

// Verifica se recebeu ID pela URL
if (!isset($_GET["id"])) {
    echo "<p>ID de usuário não informado.</p>";
    echo '<a href="listar_usuarios.php">Voltar</a>';
    exit;
}

$id = $_GET["id"];
$usuarios = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES) : [];
$usuarioEncontrado = null;

// Busca o usuário pelo ID
foreach ($usuarios as $linha) {
    list($uid, $nome, $email) = explode(";", $linha);
    if ($uid == $id) {
        $usuarioEncontrado = ["id" => $uid, "nome" => $nome, "email" => $email];
        break;
    }
}

// Se não encontrou
if (!$usuarioEncontrado) {
    echo "<p>Usuário não encontrado.</p>";
    echo '<a href="listar_usuarios.php">Voltar</a>';
    exit;
}

// Se formulário enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoNome = trim($_POST["nome"]);
    $novoEmail = trim($_POST["email"]);

    if ($novoNome != "" && $novoEmail != "") {
        // Regrava o arquivo inteiro com os dados atualizados
        $novoConteudo = "";
        foreach ($usuarios as $linha) {
            list($uid, $nome, $email) = explode(";", $linha);
            if ($uid == $id) {
                $novoConteudo .= $uid . ";" . $novoNome . ";" . $novoEmail . PHP_EOL;
            } else {
                $novoConteudo .= $linha . PHP_EOL;
            }
        }
        file_put_contents($arquivo, $novoConteudo);

        echo "<p>Usuário atualizado com sucesso!</p>";
        echo '<a href="listar_usuarios.php">Voltar à lista</a>';
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
    <title>Editar Usuário</title>
</head>
<body>
    <h2>Editar Usuário</h2>
    <form method="post">
        Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuarioEncontrado['nome']) ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= htmlspecialchars($usuarioEncontrado['email']) ?>" required><br><br>
        <button type="submit">Salvar Alterações</button>
    </form>
    <br>
    <a href="/game/usuarios/listar_usuarios.php">Cancelar</a>
    <a href="/game/index.php">Voltar ao menu</a>
</body>
</html>