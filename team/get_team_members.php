<?php
header('Content-Type: application/json');
require_once 'db.php';

// You can filter by project if needed, for now, get all users and their roles
$sql = "
    SELECT 
        u.user_id, 
        u.name, 
        u.email, 
        COALESCE(r.role_name, 'No Role') as role,
        CASE WHEN r.role_name = 'Project Manager' THEN 1 ELSE 0 END as is_admin
    FROM users u
    LEFT JOIN project_members pm ON u.user_id = pm.user_id
    LEFT JOIN project_roles r ON pm.role_id = r.role_id
";

$result = $conn->query($sql);

$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

echo json_encode($members);
$conn->close();