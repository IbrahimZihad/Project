<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$description = $data['description'];
$sprint_id = $data['sprint_id'];
$project_id = $data['project_id'];
$email = $data['email'];
$status = $data['status'] ?? 'To Do';

// Get user_id from email
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$created_by = $user['user_id'];

$stmt = $conn->prepare("INSERT INTO tasks (title, description, sprint_id, project_id, status, created_by) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiisi", $title, $description, $sprint_id, $project_id, $status, $created_by);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Insert failed"]);
}
?>
