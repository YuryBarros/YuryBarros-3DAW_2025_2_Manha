<?php
// Inclui a configuração do banco
require_once 'config.php';

// FUNÇÕES DE USUÁRIOS
function listar_usuarios(){
    $db = getDB();
    $stmt = $db->query("SELECT id, nome, email FROM usuarios ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function criar_usuario($nome, $email){
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    return $stmt->execute([$nome, $email]) ? $db->lastInsertId() : false;
}

function excluir_usuario($id){
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
    return $stmt->execute([$id]);
}

function buscar_usuario($id){
    $db = getDB();
    $stmt = $db->prepare("SELECT id, nome, email FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizar_usuario($id, $nome, $email){
    $db = getDB();
    $stmt = $db->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
    return $stmt->execute([$nome, $email, $id]);
}

// FUNÇÕES DE PERGUNTAS
function listar_perguntas(){
    $db = getDB();
    $stmt = $db->query("SELECT id, tipo, enunciado FROM perguntas ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function criar_pergunta($tipo, $enunciado, $respostas = []){
    $db = getDB();
    
    try {
        $db->beginTransaction();
        
        // Inserir pergunta
        $stmt = $db->prepare("INSERT INTO perguntas (tipo, enunciado) VALUES (?, ?)");
        $stmt->execute([$tipo, $enunciado]);
        $perguntaId = $db->lastInsertId();
        
        // Inserir respostas se for MCQ
        if ($tipo === 'mcq' && !empty($respostas)) {
            $stmtResp = $db->prepare("INSERT INTO respostas (pergunta_id, texto, correta) VALUES (?, ?, ?)");
            foreach ($respostas as $resposta) {
                $stmtResp->execute([$perguntaId, $resposta['texto'], $resposta['correta']]);
            }
        }
        
        $db->commit();
        return $perguntaId;
        
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

function listar_respostas_por_pergunta($perguntaId){
    $db = getDB();
    $stmt = $db->prepare("SELECT id, pergunta_id, texto, correta FROM respostas WHERE pergunta_id = ? ORDER BY id");
    $stmt->execute([$perguntaId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function excluir_pergunta($id){
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM perguntas WHERE id = ?");
    return $stmt->execute([$id]);
}

function buscar_pergunta($id){
    $db = getDB();
    $stmt = $db->prepare("SELECT id, tipo, enunciado FROM perguntas WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function atualizar_pergunta($id, $novo_enunciado, $novas_respostas = null){
    $db = getDB();
    
    try {
        $db->beginTransaction();
        
        // Atualizar pergunta
        $stmt = $db->prepare("UPDATE perguntas SET enunciado = ? WHERE id = ?");
        $stmt->execute([$novo_enunciado, $id]);
        
        // Se foram fornecidas novas respostas, atualizar
        if (is_array($novas_respostas)) {
            // Remover respostas antigas
            $stmtDel = $db->prepare("DELETE FROM respostas WHERE pergunta_id = ?");
            $stmtDel->execute([$id]);
            
            // Inserir novas respostas
            if (!empty($novas_respostas)) {
                $stmtIns = $db->prepare("INSERT INTO respostas (pergunta_id, texto, correta) VALUES (?, ?, ?)");
                foreach ($novas_respostas as $resposta) {
                    $stmtIns->execute([$id, $resposta['texto'], $resposta['correta']]);
                }
            }
        }
        
        $db->commit();
        return true;
        
    } catch (Exception $e) {
        $db->rollBack();
        return false;
    }
}

// PROCESSAMENTO DAS AÇÕES
$acao = $_GET['acao'] ?? ($_POST['acao'] ?? null);

if(isset($acao) && $acao === 'buscar_pergunta'){
    header('Content-Type: application/json; charset=utf-8');
    $codigo = (int)($_GET['codigo'] ?? 0);
    if(!$codigo){ echo json_encode(['sucesso'=>false]); exit; }
    $p = buscar_pergunta($codigo);
    if(!$p){ echo json_encode(['sucesso'=>false]); exit; }

    $resps = listar_respostas_por_pergunta($codigo);
    echo json_encode(['sucesso'=>true, 'pergunta'=>$p, 'respostas'=>$resps]); exit;
}

if(isset($acao) && $acao === 'salvar_pergunta' && $_SERVER['REQUEST_METHOD'] === 'POST'){
    header('Content-Type: application/json; charset=utf-8');
    $codigo = (int)($_POST['codigo'] ?? 0);
    $texto = trim($_POST['texto'] ?? '');
    if(!$codigo || $texto === ''){ echo json_encode(['sucesso'=>false, 'erro'=>'dados inválidos']); exit; }

    $tipo = $_POST['tipo'] ?? 'text';

    $novas_respostas = null;
    if($tipo === 'mcq'){
        $texts = $_POST['resp_text'] ?? [];
        $cor = $_POST['resp_correta'] ?? [];
        $novas_respostas = [];
        foreach($texts as $i => $t){ $t = trim($t); if($t === '') continue; $novas_respostas[] = ['texto'=>$t, 'correta'=> in_array((string)$i, (array)$cor) ? 1 : 0]; }
    }
    $ok = atualizar_pergunta($codigo, $texto, $novas_respostas);
    echo json_encode(['sucesso'=>$ok]); exit;
}

$mensagem = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && empty($acao)){

    if(isset($_POST['form']) && $_POST['form'] === 'criar_usuario'){
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if($nome !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)){
            criar_usuario($nome, $email);
            $mensagem = 'Usuário criado.';
        } else $mensagem = 'Erro ao criar usuário.';
    }

    if(isset($_POST['excluir_usuario_id'])){
        excluir_usuario((int)$_POST['excluir_usuario_id']);
        $mensagem = 'Usuário excluído.';
    }

    if(isset($_POST['form']) && $_POST['form'] === 'editar_usuario'){
        $id = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        if($id && $nome !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)){
            atualizar_usuario($id, $nome, $email);
            $mensagem = 'Usuário atualizado.';
        } else $mensagem = 'Erro ao editar usuário.';
    }

    if(isset($_POST['form']) && $_POST['form'] === 'criar_pergunta'){
        $tipo = $_POST['tipo'] ?? 'text';
        $enunciado = trim($_POST['enunciado'] ?? '');
        if($enunciado === ''){ $mensagem = 'Enunciado obrigatório.'; }
        else {
            $resps = [];
            if($tipo === 'mcq'){
                $texts = $_POST['resp_text'] ?? [];
                $cor = $_POST['resp_correta'] ?? [];
                foreach($texts as $i => $t){ $t = trim($t); if($t === '') continue; $resps[] = ['texto'=>$t,'correta'=> in_array((string)$i, (array)$cor) ? 1 : 0]; }
                if(count($resps) < 2) $mensagem = 'MCQ precisa de ao menos 2 respostas.';
                else { criar_pergunta($tipo, $enunciado, $resps); $mensagem = 'Pergunta criada.'; }
            } else { criar_pergunta($tipo, $enunciado, []); $mensagem = 'Pergunta criada.'; }
        }
    }

    if(isset($_POST['excluir_pergunta_id'])){
        excluir_pergunta((int)$_POST['excluir_pergunta_id']);
        $mensagem = 'Pergunta excluída.';
    }

    header('Location: '.$_SERVER['PHP_SELF']); exit;
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Jogo Corporativo</title>
<style>
body { font-family: Arial; padding: 20px; background: #f7f7fb; }
.menu button { margin-right: 10px; padding: 8px 16px; background: #2d89ef; color: white; border: none; border-radius: 4px; cursor: pointer; }
.menu button:hover { background: #1e6dc4; }
.hidden { display: none; }
table { border-collapse: collapse; margin-top: 10px; width: 100%; }
table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
input, select, textarea { margin: 5px 0; padding: 8px; width: 100%; box-sizing: border-box; }
.edit-block { margin-top: 15px; border: 1px solid #333; padding: 15px; background: white; border-radius: 4px; }
.card { background: white; padding: 15px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #e2e2e8; }
.btn { padding: 6px 10px; border: none; border-radius: 4px; cursor: pointer; margin: 2px; }
.btn-primary { background: #2d89ef; color: #fff; }
.btn-danger { background: #e74c3c; color: #fff; }
.notice { background: #eaffea; border: 1px solid #c7f0c7; padding: 8px; border-radius: 6px; margin-bottom: 10px; }
.inline-form { display: inline-block; margin: 0; }
</style>
</head>
<body>

<h1>Jogo Corporativo (Banco de Dados)</h1>

<div class="menu">
    <button onclick="mostrar('usuarios')">Usuários</button>
    <button onclick="mostrar('perguntas')">Perguntas</button>
</div>

<?php if($mensagem): ?><div class="notice"><?php echo htmlspecialchars($mensagem); ?></div><?php endif; ?>

<div id="usuarios" class="card">
    <h2>Usuários</h2>

    <h3>Criar Usuário</h3>
    <form method="post">
        <input type="hidden" name="form" value="criar_usuario">
        <input id="nome" name="nome" placeholder="Nome" required><br>
        <input id="email" name="email" placeholder="Email" type="email" required><br>
        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>

    <h3>Lista de Usuários</h3>
    <table id="tabelaUsuarios">
        <tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>
        <?php $us = listar_usuarios(); foreach($us as $u): ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo htmlspecialchars($u['nome']); ?></td>
            <td><?php echo htmlspecialchars($u['email']); ?></td>
            <td>
                <a class="btn" href="?acao=editar_usuario&id=<?php echo $u['id']; ?>">Editar</a>
                <form method="post" class="inline-form" onsubmit="return confirm('Excluir usuário?');">
                    <input type="hidden" name="excluir_usuario_id" value="<?php echo $u['id']; ?>">
                    <button class="btn btn-danger" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($us)): ?>
        <tr><td colspan="4">Nenhum usuário cadastrado</td></tr>
        <?php endif; ?>
    </table>

    <?php if(isset($_GET['acao']) && $_GET['acao']==='editar_usuario' && isset($_GET['id'])):
        $uid = (int)$_GET['id']; $uu = buscar_usuario($uid);
        if($uu):
    ?>
    <div id="editarUsuarioBox" class="edit-block">
        <h3>Editar Usuário</h3>
        <form method="post">
            <input type="hidden" name="form" value="editar_usuario">
            <input type="hidden" name="id" value="<?php echo $uu['id']; ?>">
            <input id="editNome" name="nome" value="<?php echo htmlspecialchars($uu['nome']); ?>" required><br>
            <input id="editEmail" name="email" value="<?php echo htmlspecialchars($uu['email']); ?>" type="email" required><br>
            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
            <a class="btn" href="?">Cancelar</a>
        </form>
    </div>
    <?php endif; endif; ?>
</div>

<div id="perguntas" class="card hidden">
    <h2>Perguntas</h2>

    <h3>Criar Pergunta</h3>
    <form method="post">
        <input type="hidden" name="form" value="criar_pergunta">
        <input id="textoPergunta" name="enunciado" placeholder="Texto da pergunta" required><br>
        
        <select id="tipoPergunta" name="tipo">
            <option value="text">Resposta em Texto</option>
            <option value="mcq">Múltipla Escolha</option>
        </select><br>

        <div id="opcoesMultipla" class="hidden">
            <h4>Alternativas (marque as corretas):</h4>
            <?php for($i=0;$i<4;$i++): ?>
                <div style="display: flex; align-items: center; margin: 5px 0;">
                    <input type="checkbox" name="resp_correta[]" value="<?php echo $i; ?>" style="width: auto; margin-right: 8px;">
                    <input type="text" name="resp_text[]" placeholder="Alternativa <?php echo $i+1; ?>" style="width: calc(100% - 30px);">
                </div>
            <?php endfor; ?>
        </div>

        <button class="btn btn-primary" type="submit">Salvar</button>
    </form>

    <h3>Lista de Perguntas</h3>
    <table id="tabelaPerguntas">
        <tr><th>ID</th><th>Pergunta</th><th>Tipo</th><th>Ações</th></tr>
        <?php $ps = listar_perguntas(); foreach($ps as $p): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>
            <td><?php echo htmlspecialchars($p['enunciado']); ?></td>
            <td><?php echo htmlspecialchars($p['tipo']); ?></td>
            <td>
                <button class="btn" onclick="abrirEditorPergunta(<?php echo $p['id']; ?>)">Editar (AJAX)</button>
                <form method="post" class="inline-form" onsubmit="return confirm('Excluir pergunta e respostas?');">
                    <input type="hidden" name="excluir_pergunta_id" value="<?php echo $p['id']; ?>">
                    <button class="btn btn-danger" type="submit">Excluir</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($ps)): ?>
        <tr><td colspan="4">Nenhuma pergunta cadastrada</td></tr>
        <?php endif; ?>
    </table>

    <div id="editarPerguntaBox" class="edit-block hidden">
        <h3>Editar Pergunta (AJAX)</h3>
        
        <div style="margin-bottom: 15px;">
            <label>Buscar por código:</label>
            <div style="display: flex; gap: 10px;">
                <input id="buscarCodigo" type="number" placeholder="Código da pergunta" style="flex: 1;">
                <button class="btn btn-primary" onclick="buscarPerguntaAJAX()">Buscar</button>
            </div>
        </div>

        <div id="boxEditar" style="display: none;">
            <input type="hidden" id="editCodigo">
            
            <label>Enunciado:</label>
            <textarea id="editTextoPergunta" rows="3"></textarea>
            
            <label>Tipo:</label>
            <select id="editTipoPergunta">
                <option value="text">Resposta em Texto</option>
                <option value="mcq">Múltipla Escolha</option>
            </select>

            <div id="editOpcoesMultipla" class="hidden">
                <h4>Editar Alternativas (marque as corretas):</h4>
                <?php for($i=0;$i<4;$i++): ?>
                    <div style="display: flex; align-items: center; margin: 5px 0;">
                        <input type="checkbox" id="editCorr<?php echo $i; ?>" value="<?php echo $i; ?>" style="width: auto; margin-right: 8px;">
                        <input type="text" id="editAlt<?php echo $i; ?>" placeholder="Alternativa <?php echo $i+1; ?>" style="width: calc(100% - 30px);">
                    </div>
                <?php endfor; ?>
            </div>

            <div style="margin-top: 15px;">
                <button class="btn btn-primary" onclick="salvarEdicaoPergunta()">Salvar Pergunta</button>
                <button class="btn" onclick="cancelarEdicaoPergunta()">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
function mostrar(secao) {
    document.getElementById("usuarios").classList.add("hidden");
    document.getElementById("perguntas").classList.add("hidden");
    document.getElementById(secao).classList.remove("hidden");
}

document.getElementById("tipoPergunta").addEventListener("change", function () {
    document.getElementById("opcoesMultipla").classList.toggle("hidden", this.value !== "mcq");
});

document.getElementById("editTipoPergunta").addEventListener("change", function () {
    document.getElementById("editOpcoesMultipla").classList.toggle("hidden", this.value !== "mcq");
});

function abrirEditorPergunta(codigo) {
    document.getElementById("editarPerguntaBox").classList.remove("hidden");
    document.getElementById("buscarCodigo").value = codigo;
    buscarPerguntaAJAX();
}

function buscarPerguntaAJAX() {
    const codigo = document.getElementById("buscarCodigo").value;
    if(!codigo) {
        alert("Informe o código da pergunta");
        return;
    }

    fetch('?acao=buscar_pergunta&codigo=' + encodeURIComponent(codigo))
        .then(r => r.json())
        .then(d => {
            if(!d.sucesso){ 
                alert('Pergunta não encontrada'); 
                return; 
            }
            
            const p = d.pergunta;
            document.getElementById("boxEditar").style.display = 'block';
            document.getElementById("editCodigo").value = p.id;
            document.getElementById("editTextoPergunta").value = p.enunciado;
            document.getElementById("editTipoPergunta").value = p.tipo;
            
            document.getElementById("editOpcoesMultipla").classList.toggle("hidden", p.tipo !== "mcq");
            
            if(d.respostas && d.respostas.length){
                for(let i=0; i<4; i++){
                    const resp = d.respostas[i];
                    document.getElementById('editAlt'+i).value = resp ? resp.texto : '';
                    document.getElementById('editCorr'+i).checked = resp ? (resp.correta ? true : false) : false;
                }
            } else {
                for(let i=0; i<4; i++){ 
                    document.getElementById('editAlt'+i).value = '';
                    document.getElementById('editCorr'+i).checked = false;
                }
            }
        })
        .catch(() => alert('Erro ao buscar pergunta'));
}

function salvarEdicaoPergunta() {
    const codigo = document.getElementById("editCodigo").value;
    const texto = document.getElementById("editTextoPergunta").value.trim();
    const tipo = document.getElementById("editTipoPergunta").value;
    
    if(!codigo || !texto) {
        alert("Código e enunciado são obrigatórios");
        return;
    }

    const form = new FormData();
    form.append('codigo', codigo);
    form.append('texto', texto);
    form.append('tipo', tipo);

    if(tipo === 'mcq'){
        for(let i=0; i<4; i++){
            form.append('resp_text[]', document.getElementById('editAlt'+i).value);
            if(document.getElementById('editCorr'+i).checked) {
                form.append('resp_correta[]', i);
            }
        }
    }

    fetch('?acao=salvar_pergunta', { 
        method: 'POST', 
        body: form 
    })
    .then(r => r.json())
    .then(d => {
        if(d.sucesso){ 
            alert('Pergunta alterada com sucesso'); 
            location.reload(); 
        } else { 
            alert('Falha ao salvar'); 
        }
    })
    .catch(() => alert('Erro ao salvar'));
}

function cancelarEdicaoPergunta() {
    document.getElementById("editarPerguntaBox").classList.add("hidden");
    document.getElementById("boxEditar").style.display = 'none';
}

mostrar('usuarios');
</script>

</body>
</html>