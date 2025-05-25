<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$stmt = $conn->prepare("SELECT * FROM projects WHERE project_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "project" => $row]);
} else {
    echo json_encode(["success" => false, "message" => "Project not found"]);
}
?>
