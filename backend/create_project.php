<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');

if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Project name and user email are required.']);
    exit;
}

// Find the user by email
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$userId = $user['id'];

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Insert new project
    $stmt = $pdo->prepare("INSERT INTO projects (name, created_by) VALUES (?, ?)");
    $stmt->execute([$name, $userId]);
    $projectId = $pdo->lastInsertId();

    // Insert into project_members table, mark the creator as Project Manager
    $stmt = $pdo->prepare("INSERT INTO project_members (project_id, user_id, role) VALUES (?, ?, ?)");
    $stmt->execute([$projectId, $userId, 'Project Manager']);

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Project created successfully.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
