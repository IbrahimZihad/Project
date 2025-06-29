
<?php
$conn = new mysqli("localhost", "root", "", "taskmanager");
if ($conn->connect_error) {
    die(json_encode(["error" => "DB connection failed"]));
}

// Add this for PDO support
try {
    $pdo = new PDO("mysql:host=localhost;dbname=taskmanager", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "PDO connection failed: " . $e->getMessage()]));
}
?>
