<?php
// Enable error reporting for development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Set response header to return JSON
header('Content-Type: application/json');

// Include database connection
include 'db.php';

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['id']) || empty($data['id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Missing or invalid project ID."
    ]);
    exit;
}

$project_id = (int)$data['id'];  // Always cast to integer for safety

// Prepare and execute query
$stmt = $conn->prepare("SELECT project_name FROM projects WHERE project_id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$result = $stmt->get_result();

// Return project or error
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "project" => $row
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Project not found."
    ]);
}
