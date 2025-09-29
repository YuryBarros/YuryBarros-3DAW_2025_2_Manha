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
$novoConteudo = "";
$achou = false;

// Regrava o arquivo sem o usuário selecionado
foreach ($usuarios as $linha) {
    list($uid, $nome, $email) = explode(";", $linha);
    if ($uid == $id) {
        $achou = true;
        continue; // pula esse usuário
    }
    $novoConteudo .= $linha . PHP_EOL;
}

if ($achou) {
    file_put_contents($arquivo, $novoConteudo);
    echo "<p>Usuário excluído com sucesso!</p>";
} else {
    echo "<p>Usuário não encontrado.</p>";
}
?>
<a href="/game/usuarios/listar_usuarios.php">Voltar à lista</a>