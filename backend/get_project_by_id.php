<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$project_id = $data['id'];

$stmt = $conn->prepare("SELECT project_name FROM projects WHERE project_id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "project" => $row]);
} else {
    echo json_encode(["success" => false, "message" => "Project not found"]);
}
?>
