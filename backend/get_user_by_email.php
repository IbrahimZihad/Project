<?php
header('Content-Type: application/json');
include 'db.php'; // This defines $conn (MySQLi)

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

$stmt = $conn->prepare("SELECT name FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo json_encode(['success' => true, 'name' => $user['name']]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
