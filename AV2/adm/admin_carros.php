<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Proteção de acesso ao painel admin
if (!isset($_SESSION['admin_logado'])) {
    header("Location: admin_login.php");
    exit;
}
// Adicionar novo veículo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao']) && $_POST['acao'] == 'cadastrar') {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $placa = $_POST['placa'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $img = $_POST['imagem'];
    $desc = $_POST['descricao'];

    $sql = "INSERT INTO carros_fallscar (modelo, marca, placa, preco_diaria, imagem_url, descricao, destaque_home) 
            VALUES (:mod, :mar, :pla, :pre, :img, :desc, 1)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':mod' => $modelo, ':mar' => $marca, ':pla' => $placa, 
        ':pre' => $preco, ':img' => $img, ':desc' => $desc
    ]);
    header("Location: admin_carros.php");
    exit;
}
// Excluir veículo
if (isset($_GET['deletar'])) {
    $id = $_GET['deletar'];
    // Nota: se houver reservas vinculadas, a exclusão pode falhar
    try {
        $conn->query("DELETE FROM carros_fallscar WHERE id = $id");
    } catch(Exception $e) {
        echo "<script>alert('Não é possível deletar este carro pois existem reservas ligadas a ele.');</script>";
    }
    header("Location: admin_carros.php");
    exit;
}
$carros = $conn->query("SELECT * FROM carros_fallscar ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Frota</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

            body {
                font-family: 'Poppins', sans-serif;
                background: #f1f5f9;
                padding: 20px;
                color: #1e293b;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
            }

            
            .btn-voltar {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: white;
                color: #64748b;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: 600;
                margin-bottom: 20px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
                transition: 0.3s;
            }
            .btn-voltar:hover { background: #e2e8f0; color: #333; }

            
            .form-box, table {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                width: 100%;
                overflow: hidden;
                border-collapse: collapse;
                margin-bottom: 30px;
            }

            .form-box { padding: 30px; }

            
            input, textarea {
                width: 100%;
                padding: 12px;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                margin-bottom: 15px;
                font-family: inherit;
                box-sizing: border-box;
            }
            input:focus, textarea:focus {
                outline: none;
                border-color: #0e5f22;
                box-shadow: 0 0 0 3px rgba(14, 95, 34, 0.1);
            }

            
            th {
                background-color: #0f172a;
                color: white;
                padding: 16px;
                text-align: left;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 12px;
                letter-spacing: 1px;
            }
            td {
                padding: 16px;
                border-bottom: 1px solid #f1f5f9;
                vertical-align: middle;
            }
            tr:hover td { background-color: #f8fafc; }

            
            .btn-add {
                background-color: #0e5f22;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                transition: 0.3s;
            }
            .btn-add:hover { background-color: #083d15; }

            .btn-del {
                background-color: #fee2e2;
                color: #ef4444;
                padding: 8px 16px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                transition: 0.3s;
            }
            .btn-del:hover { background-color: #fecaca; }

            
            .status { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
            .status-ok { background: #dcfce7; color: #166534; }
            .status-cancel { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

<div class="container">
    <a href="admin_dashboard.php" class="btn-voltar">← Voltar ao Painel</a>

    <div class="form-box">
        <h3 style="margin-top: 0;">Cadastrar Novo Veículo</h3>
        <form method="POST">
            <input type="hidden" name="acao" value="cadastrar">
            <div class="form-row">
                <input type="text" name="modelo" placeholder="Modelo (ex: Jeep Compass)" required>
                <input type="text" name="marca" placeholder="Marca (ex: Jeep)" required>
            </div>
            <div class="form-row">
                <input type="text" name="placa" placeholder="Placa" required>
                <input type="text" name="preco" placeholder="Preço Diária (ex: 350.00)" required>
            </div>
            <div class="form-row">
                <input type="text" name="imagem" placeholder="Caminho da Imagem (ex: assets/compass.png)" required>
            </div>
            <textarea name="descricao" rows="3" placeholder="Diferenciais do carro..." style="margin-bottom: 10px;"></textarea>
            <button type="submit" class="btn-add">Salvar Veículo</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Foto</th>
                <th>Modelo</th>
                <th>Placa</th>
                <th>Valor</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach($carros as $c): ?>
    <tr>
        <td><?php echo $c['id']; ?></td>
        <td><img src="<?php echo $c['imagem_url']; ?>" width="50" style="border-radius: 4px;"></td>
        <td><?php echo $c['modelo']; ?></td>
        <td><?php echo $c['placa']; ?></td>
        <td>R$ <?php echo number_format($c['preco_diaria'], 2, ',', '.'); ?></td>
        <td style="display: flex; gap: 10px;">
            <a href="admin_editar_carro.php?id=<?php echo $c['id']; ?>" 
               style="background-color: #f59e0b; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px;">
               <i class="fa fa-edit"></i> Editar
            </a>

            <a href="admin_carros.php?deletar=<?php echo $c['id']; ?>" class="btn-del" onclick="return confirm('Tem certeza que deseja excluir?')">
               <i class="fa fa-trash"></i> Excluir
            </a>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
</div>

</body>
</html>
