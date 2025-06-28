<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid user ID is required']);
    exit;
}

try {
    // Check if user exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $data['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    // Update user role in project_members table
    $conn->begin_transaction();

    // First remove existing roles
    $deleteStmt = $conn->prepare("DELETE FROM project_members WHERE user_id = ?");
    $deleteStmt->bind_param("i", $data['user_id']);
    $deleteStmt->execute();

    // If making admin, create a project owned by this user
    if (isset($data['is_admin']) && $data['is_admin']) {
        // Check if user already owns a project
        $projectCheck = $conn->prepare("SELECT project_id FROM projects WHERE created_by = ?");
        $projectCheck->bind_param("i", $data['user_id']);
        $projectCheck->execute();
        $projectResult = $projectCheck->get_result();

        if ($projectResult->num_rows === 0) {
            $conn->query("INSERT INTO projects (project_name, created_by) VALUES ('Admin Project', {$data['user_id']})");
        }
    }

    // Add new role if specified
    if (isset($data['role_id']) && is_numeric($data['role_id'])) {
        // Get first project to assign the role to
        $projectId = $conn->query("SELECT MIN(project_id) as id FROM projects")->fetch_assoc()['id'];

        $insertStmt = $conn->prepare("INSERT INTO project_members (project_id, user_id, role_id) VALUES (?, ?, ?)");
        $insertStmt->bind_param("iii", $projectId, $data['user_id'], $data['role_id']);
        $insertStmt->execute();
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Team member updated successfully'
    ]);
} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
