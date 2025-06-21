<?php
include 'db.php';
$data = json_decode(file_get_contents("php://input"), true);
$project_id = $data['project_id'];
$sprint_id = $data['sprint_id'] ?? null;

if ($sprint_id) {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE project_id = ? AND sprint_id = ?");
    $stmt->bind_param("ii", $project_id, $sprint_id);
} else {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE project_id = ?");
    $stmt->bind_param("i", $project_id);
}
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
echo json_encode(["tasks" => $tasks]);
?>
