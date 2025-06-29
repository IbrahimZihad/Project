<?php
// Database connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "taskmanager";

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$userResult = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$userData = $userResult->fetch_assoc();

// Fetch total projects
$projectResult = $conn->query("SELECT COUNT(*) AS total_projects FROM projects");
$projectData = $projectResult->fetch_assoc();

// Fetch total tasks
$taskResult = $conn->query("SELECT COUNT(*) AS total_tasks FROM tasks");
$taskData = $taskResult->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pani - Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <header class="header">
        <div class="logo">Pani</div>
        <nav>
            <a href="#">Product Guide</a>
            <a href="#">About</a>
            <div class="profile-icon">&#128100;</div>
        </nav>
    </header>

    <div class="container">
        <aside class="sidebar">
            <h2>Admin Panel &#9662;</h2>
            <ul>
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="userManagment.php">User Management</a></li>
                <li><a href="project_management.php">Project Management</a></li>
                <li><a href="TaskManagment.php">Task Management</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h1>Dashboard &#9662;</h1>
            </div>
            <div class="cards">
                <div class="card">
                    <h3>Total Users: <?php echo $userData['total_users']; ?></h3>
                </div>
                <div class="card">
                    <h3>Total Projects: <?php echo $projectData['total_projects']; ?></h3>
                </div>
                <div class="card">
                    <h3>Total Tasks: <?php echo $taskData['total_tasks']; ?></h3>
                </div>
            </div>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-left">
            <div class="logo">Pani</div>
            <p>Pani is a project management and issue tracking website that helps teams plan, track, and manage work. Limited is a leading home service company in Bangladesh working for democratizing digital lifestyle across the country.</p>
        </div>
        <div class="footer-center">
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="admin.php">Admin Panel</a></li>
                <li><a href="#">Product Guide</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </div>
        <div class="footer-right">
            <p>House #08, Road #10, Stadium Road, Mirpur, Dhaka, Bangladesh</p>
            <p>+88 017********00</p>
            <p>pani@gmail.com</p>
        </div>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
