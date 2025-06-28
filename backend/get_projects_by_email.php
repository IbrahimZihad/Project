<?php
// File: get_projects.php

header('Content-Type: application/json');
require_once 'db.php'; // includes $conn (MySQLi)

if (!isset($conn)) {
    echo json_encode(['success' => false, 'message' => 'Database connection not established']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$email = trim($data['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

// Get user ID
$userStmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$userStmt->bind_param("s", $email);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$userId = $user['user_id'];

// Get all projects (created by user or where user is a member)
$projectStmt = $conn->prepare("
    SELECT DISTINCT p.project_id AS id, p.project_name AS name
    FROM projects p
    LEFT JOIN project_members pm ON pm.project_id = p.project_id
    WHERE pm.user_id = ? OR p.created_by = ?
");
$projectStmt->bind_param("ii", $userId, $userId);
$projectStmt->execute();
$projectResult = $projectStmt->get_result();

$projects = [];
while ($row = $projectResult->fetch_assoc()) {
    $projects[] = $row;
}

echo json_encode(['success' => true, 'projects' => $projects]);
