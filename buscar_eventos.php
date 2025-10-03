<?php
<<<<<<< HEAD
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die(json_encode([]));
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM eventos WHERE usuario_id = $usuario_id ORDER BY data_evento, hora_inicio";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'data_evento' => $row['data_evento'],
            'hora_inicio' => $row['hora_inicio'],
            'hora_fim' => $row['hora_fim']
        ];
    }
}

echo json_encode($events);
=======
include 'config.php';

$result = $conn->query("SELECT * FROM eventos ORDER BY data_evento ASC, hora_inicio ASC");

$events = [];
while($row = $result->fetch_assoc()){
    $events[] = $row;
}

echo json_encode($events);

>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
$conn->close();
?>
