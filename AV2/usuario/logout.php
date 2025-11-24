<?php
session_start();
// Destrói todas as variáveis de sessão (desloga o usuário)
session_destroy();
// Redireciona para a página inicial
header("Location: index.php");
exit;
?>
