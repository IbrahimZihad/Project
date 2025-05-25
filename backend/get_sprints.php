<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$project_id = $data['project_id'];

$stmt = $conn->prepare("SELECT * FROM sprints WHERE project_id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

$sprints = [];
while ($row = $result->fetch_assoc()) {
    $sprints[] = $row;
}
echo json_encode(["sprints" => $sprints]);
?>
