<?php
session_start();
require_once __DIR__ . '/../db_connect.php';

// Segurança: exige login
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$mensagem = "";
$tipo_alerta = "";

// Processa atualização de perfil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    try {
        $sql = "UPDATE clientes SET nome = :nome, email = :email, telefone = :tel WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':tel', $telefone);
        $stmt->bindParam(':id', $id_usuario);
        
        if($stmt->execute()){
            $mensagem = "Dados atualizados com sucesso!";
            $tipo_alerta = "sucesso";
            $_SESSION['usuario_nome'] = $nome;
        }
    } catch (PDOException $e) {
        $mensagem = "Erro ao atualizar: " . $e->getMessage();
        $tipo_alerta = "erro";
    }
}

// 3. Busca dados do usuário
try {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $id_usuario);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar perfil.";
    exit;
}

include 'header.php';
?>

<style>
    .profile-container {
        max-width: 900px;
        margin: 50px auto;
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        padding: 0 20px;
    }

    .profile-sidebar {
        flex: 1;
        min-width: 280px;
        background: white;
        padding: 40px 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: fit-content;
        border: 1px solid #f1f5f9;
    }
    
    .avatar-circle {
        width: 120px;
        height: 120px;
        background-color: var(--primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        margin: 0 auto 20px auto;
        font-weight: 800;
        box-shadow: 0 10px 20px rgba(14, 95, 34, 0.2);
    }

    .sidebar-menu { margin-top: 30px; text-align: left; }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        color: #64748b;
        text-decoration: none;
        border-radius: 10px;
        transition: 0.3s;
        font-weight: 500;
        margin-bottom: 5px;
    }
    .sidebar-menu a:hover { background-color: #f8fafc; color: var(--primary); }
    
    .sidebar-menu a.active {
        background-color: #ecfdf5;
        color: var(--primary);
        font-weight: 700;
    }

    .profile-content {
        flex: 2;
        min-width: 350px;
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }

    .form-header {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .form-header h2 { margin: 0; color: var(--dark); font-weight: 800; }

    .form-group { margin-bottom: 25px; }
    .form-group label { display: block; margin-bottom: 8px; color: var(--dark); font-weight: 600; font-size: 14px; }
    
    .form-group input {
        width: 100%; 
        padding: 15px; 
        border: 1px solid #cbd5e1; 
        border-radius: 10px; 
        font-size: 15px;
        background: #fff;
        transition: 0.3s;
    }
    .form-group input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(14, 95, 34, 0.1);
    }
    
    .form-group input:read-only {
        background-color: #f8fafc;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .btn-save {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-save:hover {
        background-color: var(--accent);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 10px; text-align: center; font-weight: 500; }
    .alert.sucesso { background-color: #dcfce7; color: #166534; }
    .alert.erro { background-color: #fee2e2; color: #991b1b; }
</style>

<div class="profile-container">
    
    <div class="profile-sidebar">
        <div class="avatar-circle">
            <?php echo strtoupper(substr($usuario['nome'], 0, 1)); ?>
        </div>
        <h3 style="margin-bottom: 5px; color: var(--dark);"><?php echo $usuario['nome']; ?></h3>
        <p style="color: #94a3b8; font-size: 14px;">Cliente Fallscar</p>
        
        <div class="sidebar-menu">
            <a href="#" class="active"><i class="fa fa-user-edit"></i> Meus Dados</a>
            <a href="minhas_reservas.php"><i class="fa fa-history"></i> Minhas Reservas</a>
            <div style="height: 1px; background: #f1f5f9; margin: 10px 0;"></div>
            <a href="logout.php" style="color: #ef4444;"><i class="fa fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>

    <div class="profile-content">
        <div class="form-header">
            <h2>Meus Dados Cadastrais</h2>
        </div>

        <?php if($mensagem): ?>
            <div class="alert <?php echo $tipo_alerta; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form action="perfil.php" method="POST">
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" value="<?php echo $usuario['nome']; ?>" required>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>
            </div>

            <div class="form-group">
                <label>Telefone / Celular</label>
                <input type="text" name="telefone" value="<?php echo isset($usuario['telefone']) ? $usuario['telefone'] : ''; ?>" placeholder="(XX) XXXXX-XXXX">
            </div>

            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div class="form-group" style="flex: 1;">
                    <label>CPF (Não alterável)</label>
                    <input type="text" value="<?php echo $usuario['cpf']; ?>" readonly>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Data de Cadastro</label>
                    <input type="text" value="<?php echo date('d/m/Y', strtotime($usuario['data_cadastro'])); ?>" readonly>
                </div>
            </div>

            <button type="submit" class="btn-save">Salvar Alterações</button>
        </form>
    </div>

</div>

<?php include 'footer.php'; ?>
