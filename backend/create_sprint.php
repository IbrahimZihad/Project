<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$query = "INSERT INTO sprints (project_id, sprint_name, start_date, end_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $data['project_id'], $data['sprint_name'], $data['start_date'], $data['end_date']);
$stmt->execute();

echo json_encode(["success" => true, "sprint_id" => $stmt->insert_id]);
?>
