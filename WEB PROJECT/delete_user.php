<?php
// DB connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "taskmanager"; // Ensure this matches your database name

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID is provided via GET
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Step 1: Delete from project_members (foreign key)
    $stmt1 = $conn->prepare("DELETE FROM project_members WHERE user_id = ?");
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $stmt1->close();

    // Step 2: Delete from task_assignments (optional cleanup)
    $stmt2 = $conn->prepare("DELETE FROM task_assignments WHERE assigned_to = ?");
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $stmt2->close();

    // Step 3: Delete from users
    $stmt3 = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt3->bind_param("i", $user_id);
    $stmt3->execute();
    $stmt3->close();
}

// Redirect back to user management page
header("Location: userManagement.php");
exit;
?>