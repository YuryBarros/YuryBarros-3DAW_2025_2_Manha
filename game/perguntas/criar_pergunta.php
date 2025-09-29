<?php

$arquivo = "perguntas.txt";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipo = $_POST["tipo"];
    $pergunta = trim($_POST["pergunta"]);

    if ($pergunta != "" && ($tipo == "texto" || $tipo == "multipla")) {
        $id = time();

        if ($tipo == "multipla") {
            // junta as opções separadas por "|"
            $opcoes = array_filter(array_map("trim", explode("\n", $_POST["opcoes"])));
            $respostas = implode("|", $opcoes);
        } else {
            $respostas = "__"; // placeholder para pergunta aberta
        }

        $linha = $id . ";" . $tipo . ";" . $pergunta . ";" . $respostas . PHP_EOL;
        file_put_contents($arquivo, $linha, FILE_APPEND);

        echo "<p>Pergunta cadastrada com sucesso!</p>";
        echo '<a href="listar_perguntas.php">Ver lista de perguntas</a>';
        exit;
    } else {
        echo "<p>Preencha todos os campos corretamente.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Pergunta</title>
    <script>
        function toggleOpcoes() {
            let tipo = document.querySelector("input[name='tipo']:checked").value;
            document.getElementById("opcoesBox").style.display = (tipo === "multipla") ? "block" : "none";
        }
    </script>
</head>
<body>
    <h2>Cadastrar Pergunta</h2>
    <form method="post">
        Pergunta: <br>
        <textarea name="pergunta" rows="3" cols="40" required></textarea><br><br>

        Tipo de pergunta: <br>
        <label><input type="radio" name="tipo" value="multipla" checked onclick="toggleOpcoes()"> Múltipla Escolha</label><br>
        <label><input type="radio" name="tipo" value="texto" onclick="toggleOpcoes()"> Resposta em Texto</label><br><br>

        <div id="opcoesBox">
            Opções (uma por linha): <br>
            <textarea name="opcoes" rows="5" cols="30"></textarea>
        </div>
        <br>
        <button type="submit">Cadastrar</button>
    </form>
    <br>
    <a href="../index.php">Voltar ao menu</a>
</body>
</html>