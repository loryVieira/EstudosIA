<?php
// cronometro.php

// === CONFIGURAÇÃO BANCO ===
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'bd_usuarios';

// Conexão
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Criar tabela 'relacoes' se não existir
$createSql = "
CREATE TABLE IF NOT EXISTS relacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario1 INT NOT NULL,
    id_usuario2 INT NOT NULL,
    tipo ENUM('amizade','sugestao') NOT NULL,
    status ENUM('pendente','aceito') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$mysqli->query($createSql);

// Criar tabela 'tempos' se não existir
$createTempos = "
CREATE TABLE IF NOT EXISTS tempos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    tempo VARCHAR(20) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
$mysqli->query($createTempos);

// --- Usuário logado (troque pela lógica real de login) ---
session_start();
$usuario_id = $_SESSION['id'] ?? 1;

// --- Ações POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Aceitar sugestão de amizade
    if ($action === 'aceitar') {
        $idSugerido = intval($_POST['id']);
        $resp = ['ok' => false, 'msg' => 'Erro'];

        $stmt = $mysqli->prepare("
            SELECT id FROM relacoes 
            WHERE (id_usuario1 = ? AND id_usuario2 = ?) OR (id_usuario1 = ? AND id_usuario2 = ?)
            LIMIT 1
        ");
        $stmt->bind_param("iiii", $usuario_id, $idSugerido, $idSugerido, $usuario_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $stmtUp = $mysqli->prepare("UPDATE relacoes SET tipo='amizade', status='aceito' WHERE id=?");
            $stmtUp->bind_param("i", $row['id']);
            $resp['ok'] = $stmtUp->execute();
            $resp['msg'] = $resp['ok'] ? "Amizade aceita!" : "Erro ao atualizar";
        } else {
            $stmtIns = $mysqli->prepare("INSERT INTO relacoes (id_usuario1,id_usuario2,tipo,status) VALUES (?,?, 'amizade','aceito')");
            $stmtIns->bind_param("ii", $usuario_id, $idSugerido);
            $resp['ok'] = $stmtIns->execute();
            $resp['msg'] = $resp['ok'] ? "Amizade criada!" : "Erro ao inserir";
        }

        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }

    // Salvar tempo do cronômetro
    if ($action === 'salvar_tempo') {
        $tempoStr = $_POST['tempo'] ?? '';
        $resp = ['ok' => false];

        $stmt = $mysqli->prepare("INSERT INTO tempos (id_usuario, tempo) VALUES (?, ?)");
        $stmt->bind_param("is", $usuario_id, $tempoStr);
        $resp['ok'] = $stmt->execute();

        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
}

// --- Buscar sugestões ---
$sugestoes = [];
$stmtSug = $mysqli->prepare("
    SELECT u.id, u.nome, u.foto
    FROM relacoes r
    JOIN usuarios u ON u.id = r.id_usuario2
    WHERE r.id_usuario1 = ? AND r.tipo = 'sugestao'
");
$stmtSug->bind_param("i", $usuario_id);
$stmtSug->execute();
$resSug = $stmtSug->get_result();
if ($resSug) $sugestoes = $resSug->fetch_all(MYSQLI_ASSOC);

// --- Buscar amizades aceitas ---
$amizadeIds = [];
$stmtA = $mysqli->prepare("
    SELECT id_usuario1, id_usuario2 
    FROM relacoes 
    WHERE tipo='amizade' AND status='aceito' AND (id_usuario1=? OR id_usuario2=?)
");
$stmtA->bind_param("ii", $usuario_id, $usuario_id);
$stmtA->execute();
$resA = $stmtA->get_result();
while ($r = $resA->fetch_assoc()) {
    $amizadeIds[] = ($r['id_usuario1'] == $usuario_id) ? $r['id_usuario2'] : $r['id_usuario1'];
}
$amizades = [];
if ($amizadeIds) {
    $in = implode(',', array_map('intval', $amizadeIds));
    $resFriends = $mysqli->query("SELECT id,nome,foto FROM usuarios WHERE id IN ($in)");
    if ($resFriends) $amizades = $resFriends->fetch_all(MYSQLI_ASSOC);
}

// --- Função para caminho da foto ---
function foto($f) {
    if (!$f) return '/videos/default.png';
    return (str_starts_with($f, '/') || preg_match('#^https?://#',$f)) ? $f : "/".ltrim($f,'/');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cronômetro + Amizades</title>
<style>
<<<<<<< HEAD
    /* Barra toda */
::-webkit-scrollbar {
  width: 12px; /* largura da barra vertical */
  height: 12px; /* altura da barra horizontal */
}

/* Fundo da barra */
::-webkit-scrollbar-track {
  background: #f0f0f0; /* cor do fundo da barra */
  border-radius: 10px;
}

/* Parte que se move (thumb) */
::-webkit-scrollbar-thumb {
  background: #3f7c72; /* cor do "polegar" */
  border-radius: 10px;
  border: 3px solid #f0f0f0; /* dá efeito de espaçamento */
}

/* Thumb ao passar o mouse */
::-webkit-scrollbar-thumb:hover {
  background: #2a5c55;
}
/* Fonte */
@font-face { font-family: 'SimpleHandmade'; src: url(/fonts/SimpleHandmade.ttf); }
=======
body{font-family:Arial,sans-serif;background:#f4fdfb;margin:0;padding:20px;text-align:center;}
.robo{max-width:380px;width:80%;margin:10px auto;display:block;}
.cronometro{margin:20px 0;}
#tempo{font-size:2.6rem;color:#3f7c72;padding:10px 20px;background:#fff;border-radius:12px;border:1px solid #bdebe3;}
.cronobtn{background:#3f7c72;color:#fff;border:none;padding:8px 12px;margin:5px;border-radius:10px;cursor:pointer;}
.cronobtn:hover{background:#2a5c55}
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355

/* Wrapper centraliza o robô e serve de referência para o painel */
.wrapper {
  position: relative;
  display: inline-block; /* conteúdo centralizado horizontalmente */
}

/* Robô centralizado */
.robo {
  display: block;
  max-width: 380px;
  width: 80%;
}

/* Painel lateral esquerdo do robô */
.painel {
    position: fixed;        /* fixo na tela */
    left: 120px;             /* distância da borda esquerda da tela */
    top: 25%;               /* centraliza verticalmente */
    transform: translateY(-50%); /* ajusta exatamente no meio vertical */
    
    width: 260px;
    max-height: 80vh;
    overflow: auto;
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    border: 1px solid #e6f3ef;
    text-align: left;
    z-index: 10;
}

/* Títulos do painel */
.painel h4 {
    margin: 6px 0;
    font-size: 1rem;
    color: #3f7c72;
    border-bottom: 1px solid #eee;
    padding-bottom: 4px;
}

/* Lista de usuários */
.lista {
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Cada usuário na lista */
.usuario {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 6px 0;
}

/* Foto do usuário */
.usuario img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #3f7c72;
}
header {
  position: fixed; top:0; left:0; width:100%; height:70px;
  background:#ffffffcc; display:flex; justify-content:space-between; align-items:center;
  padding:0 2rem; box-shadow:0 2px 5px rgba(0,0,0,0.1); z-index:1000;
}
    header .logo img{height:450px;width:auto;display:block; margin-left: -85px;}


    nav ul{list-style:none; display:flex; align-items:center; gap:20px; margin:0;}
nav ul li a{ text-decoration:none; color:black;  padding:5px 10px; border-radius:8px; transition:.3s;}

/* Nome do usuário */
.nome {
    flex: 1;
    font-size: 0.95rem;
}

/* Botão de ação do usuário */
.usuario button {
    background: #3f7c72;
    color: #fff;
    border: none;
    padding: 5px 8px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
}

.usuario button:hover {
    background: #2a5c55;
}

/* Mensagem quando a lista está vazia */
.empty {
    font-size: 0.85rem;
    color: #777;
    margin: 6px 0;
}

/* Painel de melhores tempos do usuário */
.painel-tempos {
    position: fixed;        /* fixo na tela */
    right: 120px;            /* distância da borda esquerda da tela */
    top: 25%;               /* centraliza verticalmente com foco no meio do lado esquerdo */
    transform: translateY(-50%);
    
    width: 260px;
    max-height: 80vh;
    overflow: auto;
    background: #fff;
    border-radius: 12px;
    padding: 12px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    border: 1px solid #e6f3ef;
    text-align: left;
    z-index: 10;
}

/* Título do painel de tempos */
.painel-tempos h4 {
    margin: 6px 0;
    font-size: 1rem;
    color: #3f7c72;
    border-bottom: 1px solid #eee;
    padding-bottom: 4px;
}

/* Lista de tempos */
.lista-tempos {
    list-style: none;
    margin: 0;
    padding: 0;
}

/* Cada tempo na lista */
.tempo-item {
    display: flex;
    justify-content: space-between;
    gap: 8px;
    margin: 6px 0;
    font-size: 0.95rem;
    color: #555;
    padding: 4px 6px;
    border-bottom: 1px solid #eee;
}

.tempo-item:last-child {
    border-bottom: none;
}

</style>
</head>
<body>
<<<<<<< HEAD
  <header>
    <div class="logo"><img src="/imagens/logoatual.png" alt="Logo"></div>
    <nav>
      <ul>
          <li><a href="/inicio.php">Voltar</a></li>
      </ul>
    </nav>
  </header>
<!-- Robo dormindo -->
<div class="robo-container">
    <img src="/videos/Robo_dormindo.gif" alt="Robo Dormindo">
=======

<img class="robo" src="/videos/Robo_dormindo.gif" alt="Robo Dormindo">

<div class="cronometro">
  <div id="tempo">00:00:00</div><br>
  <button class="cronobtn" id="startBtn">Iniciar</button>
  <button class="cronobtn" id="stopBtn">Parar</button>
  <button class="cronobtn" id="resetBtn">Resetar</button>
  <button class="cronobtn" id="salvarBtn">Salvar Tempo</button>
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
</div>

<div class="painel">
  <h4>Sugestões de Amizade</h4>
  <ul class="lista" id="lista-sug">
    <?php if($sugestoes): foreach($sugestoes as $s): ?>
      <li class="usuario" data-id="<?= $s['id'] ?>">
        <img src="<?= foto($s['foto']) ?>">
        <div class="nome"><?= htmlspecialchars($s['nome']) ?></div>
        <button class="aceitarBtn" data-id="<?= $s['id'] ?>">Aceitar</button>
      </li>
    <?php endforeach; else: ?>
      <div class="empty">Nenhuma sugestão.</div>
    <?php endif; ?>
  </ul>
  <h4>Amizades</h4>
  <ul class="lista" id="lista-amigos">
    <?php if($amizades): foreach($amizades as $a): ?>
      <li class="usuario">
        <img src="<?= foto($a['foto']) ?>">
        <div class="nome"><?= htmlspecialchars($a['nome']) ?></div>
      </li>
    <?php endforeach; else: ?>
      <div class="empty">Nenhum amigo ainda.</div>
    <?php endif; ?>
  </ul>
</div>
<div class="painel-tempos">
<h4>Melhores Tempos</h4>
<table id="tabelaTempos">
  <thead>
    <tr><th>Tempo</th><th>Data</th></tr>
  </thead>
  <tbody>
    </div>
    <?php
    $res = $mysqli->query("SELECT tempo, criado_em FROM tempos WHERE id_usuario=$usuario_id ORDER BY criado_em DESC LIMIT 10");
    while($row = $res->fetch_assoc()){
        echo "<tr><td>{$row['tempo']}</td><td>{$row['criado_em']}</td></tr>";
    }
    ?>
  </tbody>
</table>

<script>
let seg=0, timer=null;
const tempo=document.getElementById('tempo');

// Formatar tempo HH:MM:SS
function fmt(s){
    let h=Math.floor(s/3600);
    let m=Math.floor((s%3600)/60);
    let ss=s%60;
    return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}:${String(ss).padStart(2,'0')}`;
}

// Incrementa tempo
function tick(){ seg++; tempo.textContent=fmt(seg); }

// Start, Stop e Reset
document.getElementById('startBtn').onclick = ()=>{
    if(!timer) timer=setInterval(tick, 500); // 2x mais rápido
}
document.getElementById('stopBtn').onclick = ()=>{
    clearInterval(timer); timer=null;
}
document.getElementById('resetBtn').onclick = ()=>{
    clearInterval(timer); timer=null; seg=0; tempo.textContent=fmt(seg);
}

// Botão Salvar Tempo
document.getElementById('salvarBtn').onclick = async ()=>{
    const fd=new FormData();
    fd.append('action','salvar_tempo');
    fd.append('tempo', tempo.textContent);
    const r=await fetch(location.href,{method:'POST', body:fd});
    const d=await r.json();
    if(d.ok) alert("Tempo salvo com sucesso!");
    else alert("Erro ao salvar tempo");
}

// Aceitar amizade
document.querySelectorAll('.aceitarBtn').forEach(btn=>{
    btn.onclick=async()=>{
        const id=btn.dataset.id;
        btn.disabled=true; btn.textContent='...';
        const fd=new FormData(); fd.append('action','aceitar'); fd.append('id',id);
        const r=await fetch(location.href,{method:'POST',body:fd}); const d=await r.json();
        if(d.ok){
            const li=btn.closest('.usuario'); btn.remove();
            document.getElementById('lista-amigos').appendChild(li);
        }else{alert(d.msg); btn.disabled=false; btn.textContent='Aceitar';}
    }
});
</script>
</body>
</html>