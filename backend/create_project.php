<?php
// Set response content type to JSON
header("Content-Type: application/json");

// Include your database connection file here
include 'db.php'; // Make sure this file creates a $conn (mysqli) object

// Read and decode the incoming JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['name'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Required fields are missing.'
    ]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$name = $conn->real_escape_string($data['name']);

// Step 1: Find user_id by email
$userQuery = "SELECT user_id FROM users WHERE email = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found.'
    ]);
    exit;
}

$user = $result->fetch_assoc();
$user_id = $user['user_id'];

// Step 2: Insert project into the projects table
$insertQuery = "INSERT INTO projects (project_name, created_by) VALUES (?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("si", $name, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Project created successfully.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to create project: ' . $conn->error
    ]);
}

$conn->close();
?>
