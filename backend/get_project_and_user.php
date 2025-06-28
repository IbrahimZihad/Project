<?php
require_once 'db.php';

$id = $_GET['id'];
$email = $_GET['email'];

$query = "SELECT p.project_name, u.name as user_name
          FROM projects p
          JOIN users u ON p.created_by = u.user_id
          WHERE p.project_id = ? AND u.email = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("is", $id, $email);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
?>
