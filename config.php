<?php
<<<<<<< HEAD
$servername = "localhost";
$username = "root"; // seu usuário MySQL
$password = "";     // sua senha
$dbname = "bd_usuarios";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
=======
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bd_usuarios"; // sua database existente

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
>>>>>>> 8e99e8bac065e709db83198d5c67922bcc54d355
}
?>
