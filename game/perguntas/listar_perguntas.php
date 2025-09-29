<?php

$arquivo = "perguntas.txt";
$perguntas = [];

if (file_exists($arquivo)) {
    $perguntas = file($arquivo, FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Perguntas</title>
</head>
<body>
    <h2>Perguntas Cadastradas</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Pergunta</th>
            <th>Respostas/Opcões</th>
            <th>Ações</th>
        </tr>
        <?php
        if (count($perguntas) > 0) {
            foreach ($perguntas as $linha) {
                list($id, $tipo, $pergunta, $respostas) = explode(";", $linha);

                echo "<tr>
                        <td>$id</td>
                        <td>$tipo</td>
                        <td>$pergunta</td>
                        <td>";

                if ($tipo == "multipla") {
                    echo str_replace("|", ", ", $respostas);
                } else {
                    echo "Resposta aberta (texto livre)";
                }

                echo "</td>
                        <td>
                            <a href='editar_pergunta.php?id=$id'>Editar</a> | 
                            <a href='excluir_pergunta.php?id=$id' onclick='return confirm(\"Tem certeza que deseja excluir?\");'>Excluir</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Nenhuma pergunta cadastrada.</td></tr>";
        }
        ?>
    </table>
    <br>
    <a href="../index.php">Voltar ao menu</a>
</body>
</html>