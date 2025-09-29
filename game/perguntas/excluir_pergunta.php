<?php

$arquivo = "perguntas.txt";

if (!isset($_GET["id"])) {
    echo "<p>ID da pergunta não informado.</p>";
    echo '<a href="listar_perguntas.php">Voltar</a>';
    exit;
}

$id = $_GET["id"];
$perguntas = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES) : [];
$novoConteudo = "";
$achou = false;

foreach ($perguntas as $linha) {
    list($pid, $tipo, $texto, $respostas) = explode(";", $linha);
    if ($pid == $id) {
        $achou = true;
        continue;
    }
    $novoConteudo .= $linha . PHP_EOL;
}

if ($achou) {
    file_put_contents($arquivo, $novoConteudo);
    echo "<p>Pergunta excluída com sucesso!</p>";
} else {
    echo "<p>Pergunta não encontrada.</p>";
}
?>
<a href="listar_perguntas.php">Voltar à lista</a>