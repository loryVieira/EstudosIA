<?php
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
        echo "ok";
    } else {
        echo "erro";
    }
    $stmt->close();
} else {
    echo "erro";
}
$conn->close();
?>
