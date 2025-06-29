
<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Valid email is required']);
    exit;
}

if (!isset($data['name']) || empty(trim($data['name']))) {
    http_response_code(400);
    echo json_encode(['error' => 'Name is required']);
    exit;
}

try {
    // Check if user already exists
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'User with this email already exists']);
        exit;
    }

    // Insert new user (in a real app, you would send an invitation email)
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, 'temp_password')");
    $stmt->bind_param("ss", $data['name'], $data['email']);
    $stmt->execute();

    $newUserId = $stmt->insert_id;

    // If this is the first user, make them admin by creating a project
    $countQuery = $conn->query("SELECT COUNT(*) as count FROM projects");
    $countResult = $countQuery->fetch_assoc();

    if ($countResult['count'] == 0) {
        $conn->query("INSERT INTO projects (project_name, created_by) VALUES ('Default Project', $newUserId)");
    }

    echo json_encode([
        'success' => true,
        'message' => 'Team member added successfully',
        'user_id' => $newUserId
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>