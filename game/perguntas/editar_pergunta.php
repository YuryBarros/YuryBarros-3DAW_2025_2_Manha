<?php

$arquivo = "perguntas.txt";

if (!isset($_GET["id"])) {
    echo "<p>ID da pergunta não informado.</p>";
    echo '<a href="listar_perguntas.php">Voltar</a>';
    exit;
}

$id = $_GET["id"];
$perguntas = file_exists($arquivo) ? file($arquivo, FILE_IGNORE_NEW_LINES) : [];
$perguntaEncontrada = null;

// Busca pergunta
foreach ($perguntas as $linha) {
    list($pid, $tipo, $texto, $respostas) = explode(";", $linha);
    if ($pid == $id) {
        $perguntaEncontrada = ["id" => $pid, "tipo" => $tipo, "texto" => $texto, "respostas" => $respostas];
        break;
    }
}

if (!$perguntaEncontrada) {
    echo "<p>Pergunta não encontrada.</p>";
    echo '<a href="listar_perguntas.php">Voltar</a>';
    exit;
}

// Se salvar edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $novoTexto = trim($_POST["pergunta"]);
    $novoTipo = $_POST["tipo"];
    $novasRespostas = ($novoTipo == "multipla") ? implode("|", array_filter(array_map("trim", explode("\n", $_POST["opcoes"])))) : "__";

    $novoConteudo = "";
    foreach ($perguntas as $linha) {
        list($pid, $tipo, $texto, $respostas) = explode(";", $linha);
        if ($pid == $id) {
            $novoConteudo .= $pid . ";" . $novoTipo . ";" . $novoTexto . ";" . $novasRespostas . PHP_EOL;
        } else {
            $novoConteudo .= $linha . PHP_EOL;
        }
    }

    file_put_contents($arquivo, $novoConteudo);

    echo "<p>Pergunta atualizada com sucesso!</p>";
    echo '<a href="listar_perguntas.php">Voltar à lista</a>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Pergunta</title>
    <script>
        function toggleOpcoes() {
            let tipo = document.querySelector("input[name='tipo']:checked").value;
            document.getElementById("opcoesBox").style.display = (tipo === "multipla") ? "block" : "none";
        }
    </script>
</head>
<body onload="toggleOpcoes()">
    <h2>Editar Pergunta</h2>
    <form method="post">
        Pergunta: <br>
        <textarea name="pergunta" rows="3" cols="40" required><?= htmlspecialchars($perguntaEncontrada['texto']) ?></textarea><br><br>

        Tipo de pergunta: <br>
        <label><input type="radio" name="tipo" value="multipla" <?= $perguntaEncontrada['tipo'] == "multipla" ? "checked" : "" ?> onclick="toggleOpcoes()"> Múltipla Escolha</label><br>
        <label><input type="radio" name="tipo" value="texto" <?= $perguntaEncontrada['tipo'] == "texto" ? "checked" : "" ?> onclick="toggleOpcoes()"> Resposta em Texto</label><br><br>

        <div id="opcoesBox">
            Opções (uma por linha): <br>
            <textarea name="opcoes" rows="5" cols="30"><?php
                if ($perguntaEncontrada['tipo'] == "multipla") {
                    echo str_replace("|", "\n", $perguntaEncontrada['respostas']);
                }
            ?></textarea>
        </div>
        <br>
        <button type="submit">Salvar Alterações</button>
    </form>
    <br>
    <a href="listar_perguntas.php">Cancelar</a>
</body>
</html>