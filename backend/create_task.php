<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'];
$description = $data['description'];
$sprint_id = $data['sprint_id'];
$project_id = $data['project_id'];
$status = "To Do";

$stmt = $conn->prepare("INSERT INTO tasks (title, description, sprint_id, project_id, status) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssiis", $title, $description, $sprint_id, $project_id, $status);
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
