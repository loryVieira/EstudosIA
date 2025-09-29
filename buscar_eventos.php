<?php
include 'config.php';

$result = $conn->query("SELECT * FROM eventos ORDER BY data_evento ASC, hora_inicio ASC");

$events = [];
while($row = $result->fetch_assoc()){
    $events[] = $row;
}

echo json_encode($events);

$conn->close();
?>
