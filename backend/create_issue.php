<?php
$data = json_decode(file_get_contents("php://input"), true);

include 'db.php';

$stmt = $conn->prepare("INSERT INTO tasks (project_id, sprint_id, title, description, status, created_by) VALUES (?, ?, ?, ?, 'To Do', ?)");
$stmt->bind_param("iissi", $data["project_id"], $data["sprint_id"], $data["title"], $data["description"], $data["created_by"]);
$stmt->execute();

echo json_encode(["task_id" => $stmt->insert_id]);

$stmt->close();
$conn->close();
?>
