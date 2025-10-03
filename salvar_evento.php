<?php
<<<<<<< HEAD
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die("erro_sessao"); // usuário não logado
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"] ?? '';
    $data = $_POST["data_evento"] ?? '';
    $hora_inicio = $_POST["hora_inicio"] ?? '';
    $hora_fim = $_POST["hora_fim"] ?? '';
    $usuario_id = $_SESSION['usuario_id'];

    $stmt = $conn->prepare("INSERT INTO eventos (titulo, data_evento, hora_inicio, hora_fim, usuario_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $titulo, $data, $hora_inicio, $hora_fim, $usuario_id);

    if ($stmt->execute()) {
=======
include 'config.php';

// Força fuso horário do Brasil
date_default_timezone_set('America/Sao_Paulo');

$titulo = $_POST['titulo'] ?? '';
$data_evento = $_POST['data_evento'] ?? ''; // espera YYYY-MM-DD
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fim = $_POST['hora_fim'] ?? '';

if($titulo && $data_evento && $hora_inicio && $hora_fim){
    // Converte para DateTime (para garantir fuso)
    $dt = DateTime::createFromFormat('Y-m-d', $data_evento);
    if(!$dt){
        echo "erro";
        exit;
    }
    $data_formatada = $dt->format('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO eventos (titulo, data_evento, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $data_formatada, $hora_inicio, $hora_fim);

    if($stmt->execute()){
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
        echo "ok";
    } else {
        echo "erro";
    }
    $stmt->close();
<<<<<<< HEAD
=======
} else {
    echo "erro";
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
}
$conn->close();
?>
