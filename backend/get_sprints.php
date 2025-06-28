<?php
require_once 'db.php';

$project_id = $_GET['project_id'];

$query = "SELECT sprint_id, sprint_name, start_date, end_date FROM sprints WHERE project_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

$sprints = [];
while ($row = $result->fetch_assoc()) {
    $sprints[] = $row;
}

echo json_encode($sprints);
?>
