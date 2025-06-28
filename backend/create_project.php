<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';
$name = $data['name'] ?? '';

if ($email == '' || $name == '') {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
    exit;
}

// Step 1: Find user_id by email
$query = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
$query->execute([$email]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$user_id = $user['user_id'];

// Step 2: Insert project into the projects table
$query = $pdo->prepare("INSERT INTO projects (project_name, created_by) VALUES (?, ?)");

if ($query->execute([$name, $user_id])) {
    echo json_encode(['success' => true, 'message' => 'Project created successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create project.']);
}
?>
