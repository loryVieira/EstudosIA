<?php
<<<<<<< HEAD
session_start();
include 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    die("erro_sessao");
}

$id = $_POST['id'] ?? 0;
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("DELETE FROM eventos WHERE id=? AND usuario_id=?");
$stmt->bind_param("ii", $id, $usuario_id);

if ($stmt->execute()) {
    echo "ok";
} else {
    echo "erro";
}
$stmt->close();
$conn->close();
?>
=======
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
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
