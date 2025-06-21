<?php
header('Content-Type: application/json');
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$projectId = $data['id'] ?? null;

if (!$projectId) {
    echo json_encode(['success' => false, 'message' => 'Project ID is required']);
    exit;
}

try {
    // Delete from project_members first to avoid foreign key constraint
    $stmt1 = $pdo->prepare("DELETE FROM project_members WHERE project_id = ?");
    $stmt1->execute([$projectId]);

    // Delete from tasks
    $stmt2 = $pdo->prepare("DELETE FROM tasks WHERE project_id = ?");
    $stmt2->execute([$projectId]);

    // Delete from sprints
    $stmt3 = $pdo->prepare("DELETE FROM sprints WHERE project_id = ?");
    $stmt3->execute([$projectId]);

    // Delete the project
    $stmt = $pdo->prepare("DELETE FROM projects WHERE project_id = ?");
    $stmt->execute([$projectId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>