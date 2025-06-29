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

// Get total users
$stmt_users = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $stmt_users->fetch()['total_users'];

// Get total projects
$stmt_projects = $pdo->query("SELECT COUNT(*) AS total_projects FROM projects");
$total_projects = $stmt_projects->fetch()['total_projects'];

// Get total tasks
$stmt_tasks = $pdo->query("SELECT COUNT(*) AS total_tasks FROM tasks");
$total_tasks = $stmt_tasks->fetch()['total_tasks'];
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
                <li><a href="#">Dashboard</a></li>
                <li><a href="#">User Management</a></li>
                <li><a href="#">Project Management</a></li>
                <li><a href="#">Task Management</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h1>Dashboard &#9662;</h1>
            </div>
            <div class="cards">
                <div class="card">
                    <h3>Total Users: <?php echo $total_users; ?></h3>
                </div>
                <div class="card">
                    <h3>Total Projects: <?php echo $total_projects; ?></h3>
                </div>
                <div class="card">
                    <h3>Total Tasks: <?php echo $total_tasks; ?></h3>
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
                <li><a href="#">Admin Panel</a></li>
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
