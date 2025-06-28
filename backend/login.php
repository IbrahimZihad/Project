<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
require_once 'db.php';

$email = $data['email'];
$password = $data['password'];

$query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$query->execute([$email]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'user_id' => $user['user_id'], 'name' => $user['name']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}
?>
