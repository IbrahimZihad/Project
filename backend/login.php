<!-- <?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
require_once 'db.php';

$email = $data['email'];
$password = $data['password'];

$query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$query->execute([$email]);
$user = $query->fetch();

if ($user && password_verify($password, $user['password'])) {
    echo json_encode(['success' => true, 'user_id' => $user['user_id'], 'name' => $user['name']]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}
?> -->
<?php
require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];
$password = $data['password'];

$response = ['success' => false, 'message' => 'Invalid credentials'];

$stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
        $response = [
            'success' => true,
            'user_id' => $user['user_id'],
            'name' => $user['name']
        ];
    } else {
        $response['message'] = "Incorrect password";
    }
} else {
    $response['message'] = "User not found";
}

echo json_encode($response);
?>
