<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
require_once 'db.php';

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$query = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

try {
    $query->execute([$name, $email, $password]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Email may already exist.']);
}
?>
