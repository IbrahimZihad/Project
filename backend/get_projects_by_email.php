<?php
header('Content-Type: application/json');
require_once 'db.php'; // Assumes $pdo is defined here

if (!isset($pdo)) {
    echo json_encode(['success' => false, 'message' => 'Database connection not established']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Get the user ID using correct field name
$userStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$userStmt->execute([$email]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$userId = $user['user_id'];

// Get all projects associated with the user (as creator or team member)
$projectStmt = $pdo->prepare("
    SELECT DISTINCT p.project_id AS id, p.project_name AS name
    FROM projects p
    LEFT JOIN project_members pm ON pm.project_id = p.project_id
    WHERE pm.user_id = ? OR p.created_by = ?
");
$projectStmt->execute([$userId, $userId]);
$projects = $projectStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'projects' => $projects]);
?>