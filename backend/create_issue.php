<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$query = "INSERT INTO tasks (project_id, sprint_id, title, description, created_by) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iissi", $data['project_id'], $data['sprint_id'], $data['title'], $data['description'], $data['created_by']);
$stmt->execute();

echo json_encode(["success" => true, "task_id" => $stmt->insert_id]);
?>
