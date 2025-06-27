<?php
$projectId = $_GET['id'];
$email = $_GET['email'];

require_once 'db.php';

$stmt = $conn->prepare("
    SELECT p.project_name, u.name 
    FROM projects p
    JOIN users u ON u.user_id = p.created_by
    WHERE p.project_id = ? AND u.email = ?
");
$stmt->bind_param("is", $projectId, $email);
$stmt->execute();
$stmt->bind_result($projectName, $userName);
$stmt->fetch();

echo json_encode(["project_name" => $projectName, "user_name" => $userName]);

$stmt->close();
$conn->close();
?>
