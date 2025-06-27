<?php
$data = json_decode(file_get_contents("php://input"), true);

require_once 'db.php';

$stmt = $conn->prepare("UPDATE sprints SET start_date = ?, end_date = ? WHERE sprint_id = ?");
$stmt->bind_param("ssi", $data["start_date"], $data["end_date"], $data["sprint_id"]);
$success = $stmt->execute();

echo json_encode(["success" => $success]);

$stmt->close();
$conn->close();
?>
