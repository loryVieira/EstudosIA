<?php
// excluir_evento.php
header('Content-Type: text/plain'); // para o JS ler como texto simples

include 'config.php'; // seu arquivo de conexão ao banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo 'erro';
        exit;
    }

    $id = intval($_POST['id']); // garante que seja inteiro

    $sql = "DELETE FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo 'ok';
    } else {
        echo 'erro';
    }

    $stmt->close();
    $conn->close();
} else {
    echo 'erro';
}
