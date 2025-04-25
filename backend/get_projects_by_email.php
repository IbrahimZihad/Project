<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';

if ($email == '') {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Get the user ID from email
$userStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$userStmt->execute([$email]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$userId = $user['id'];

// Get all projects associated with the user (as manager, assistant, or team member)
$projectStmt = $pdo->prepare("
    SELECT DISTINCT p.id, p.name 
    FROM projects p
    LEFT JOIN project_assignments pa ON pa.project_id = p.id
    WHERE pa.user_id = ? OR p.created_by = ?
");
$projectStmt->execute([$userId, $userId]);
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'projects' => $projects]);
?>
