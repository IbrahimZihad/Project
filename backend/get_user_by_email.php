<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';

if ($email == '') {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

$query = $pdo->prepare("SELECT name FROM users WHERE email = ?");
$query->execute([$email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode(['success' => true, 'name' => $user['name']]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
