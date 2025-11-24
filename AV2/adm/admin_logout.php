<?php
session_start();
session_destroy(); // Ou destrói tudo para garantir
// Logout do admin
unset($_SESSION['admin_logado']);
unset($_SESSION['admin_nome']);
unset($_SESSION['admin_id']);
session_destroy(); // Ou destrói tudo para garantir

header("Location: admin_login.php");
exit;
?>
