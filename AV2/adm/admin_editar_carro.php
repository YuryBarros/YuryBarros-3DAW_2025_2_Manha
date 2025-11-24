<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Proteção de acesso ao admin
if (!isset($_SESSION['admin_logado'])) {
    header("Location: admin_login.php");
    exit;
}
// Verifica se veio um ID para editar
if (!isset($_GET['id'])) {
    header("Location: admin_carros.php");
    exit;
}

$id = $_GET['id'];

// Salva alterações do veículo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modelo = $_POST['modelo'];
    $marca = $_POST['marca'];
    $placa = $_POST['placa'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $img = $_POST['imagem'];
    $desc = $_POST['descricao'];

    try {
        $sql = "UPDATE carros_fallscar 
                SET modelo = :mod, marca = :mar, placa = :pla, preco_diaria = :pre, imagem_url = :img, descricao = :desc 
                WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':mod' => $modelo, ':mar' => $marca, ':pla' => $placa, 
            ':pre' => $preco, ':img' => $img, ':desc' => $desc, ':id' => $id
        ]);

        // Redireciona de volta para a lista
        header("Location: admin_carros.php");
        exit;

    } catch (PDOException $e) {
        $erro = "Erro ao atualizar: " . $e->getMessage();
    }
}

// 3. Buscar os dados atuais do carro para preencher o formulário
$stmt = $conn->prepare("SELECT * FROM carros_fallscar WHERE id = :id");
$stmt->execute([':id' => $id]);
$carro = $stmt->fetch(PDO::FETCH_ASSOC);

// Se o carro não existir (ID inválido), volta
if (!$carro) {
    header("Location: admin_carros.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Carro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f5f9;
            padding: 20px;
            color: #1e293b;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
        }

        .form-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        h2 { margin-top: 0; color: #0e5f22; border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 25px; }

        .form-row { display: flex; gap: 20px; margin-bottom: 15px; }
        
        label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 14px; color: #64748b; }
        
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-family: inherit;
            box-sizing: border-box;
            font-size: 14px;
        }
        
        input:focus, textarea:focus {
            outline: none;
            border-color: #0e5f22;
            box-shadow: 0 0 0 3px rgba(14, 95, 34, 0.1);
        }

        .btn-save {
            background-color: #0e5f22;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: 0.3s;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .btn-save:hover { background-color: #083d15; transform: translateY(-2px); }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-cancel:hover { text-decoration: underline; color: #ef4444; }

        .preview-img {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-box">
        <h2><i class="fa fa-edit"></i> Editar Veículo: <?php echo $carro['modelo']; ?></h2>

        <div class="preview-img">
            <p style="font-size: 12px; color: #999; margin: 0 0 5px 0;">Imagem Atual</p>
            <img src="<?php echo $carro['imagem_url']; ?>" height="80" style="border-radius: 5px;">
        </div>

        <form method="POST">
            
            <div class="form-row">
                <div style="flex: 1;">
                    <label>Modelo</label>
                    <input type="text" name="modelo" value="<?php echo $carro['modelo']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Marca</label>
                    <input type="text" name="marca" value="<?php echo $carro['marca']; ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div style="flex: 1;">
                    <label>Placa</label>
                    <input type="text" name="placa" value="<?php echo $carro['placa']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Preço Diária (R$)</label>
                    <input type="text" name="preco" value="<?php echo $carro['preco_diaria']; ?>" required>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label>URL da Imagem</label>
                <input type="text" name="imagem" value="<?php echo $carro['imagem_url']; ?>" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label>Descrição / Diferenciais</label>
                <textarea name="descricao" rows="4"><?php echo $carro['descricao']; ?></textarea>
            </div>

            <button type="submit" class="btn-save">
                <i class="fa fa-save"></i> Salvar Alterações
            </button>

            <a href="admin_carros.php" class="btn-cancel">Cancelar e Voltar</a>

        </form>
    </div>
</div>

</body>
</html>
