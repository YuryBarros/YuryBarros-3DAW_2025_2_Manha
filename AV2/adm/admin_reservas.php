<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

if (!isset($_SESSION['admin_logado'])) {
    header("Location: admin_login.php");
    exit;
}

// Consulta: reservas com cliente e carro
$sql = "SELECT r.id, r.data_retirada, r.data_entrega, r.valor_total, r.status,
               cli.nome as nome_cliente, 
               car.modelo as nome_carro 
        FROM reservas r
        JOIN clientes cli ON r.id_cliente = cli.id
        JOIN carros_fallscar car ON r.id_carro = car.id
        ORDER BY r.data_reserva DESC";

$reservas = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Reservas</title>
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
    <h2 style="margin-top:0;">Reservas Realizadas</h2>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Carro</th>
                <th>Retirada</th>
                <th>Entrega</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reservas as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo $r['nome_cliente']; ?></td>
                <td><?php echo $r['nome_carro']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($r['data_retirada'])); ?></td>
                <td><?php echo date('d/m/Y', strtotime($r['data_entrega'])); ?></td>
                <td><strong>R$ <?php echo number_format($r['valor_total'], 2, ',', '.'); ?></strong></td>
                <td><span class="status status-ok"><?php echo $r['status']; ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
