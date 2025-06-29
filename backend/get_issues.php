<?php
header('Content-Type: application/json');
require_once 'db.php';

$sprint_id = $_GET['sprint_id'] ?? null;

if (!$sprint_id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT task_id, title, status FROM tasks WHERE sprint_id = ?");
$stmt->bind_param("i", $sprint_id);
$stmt->execute();
$result = $stmt->get_result();

$issues = [];
while ($row = $result->fetch_assoc()) {
    $issues[] = [
        'task_id' => $row['task_id'],
        'title' => $row['title'],
        'status' => $row['status']
    ];
}

echo json_encode($issues);
?>
