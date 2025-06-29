<?php
// DB connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "taskmanager"; // Ensure this matches your database name

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_user"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash("123456", PASSWORD_DEFAULT); // default password

    $insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $name, $email, $password);
    $insert->execute();
    $insert->close();

    // Refresh to show updated table
    header("Location: userManagment.php");
    exit;
}

// Fetch user list with roles
$query = "
    SELECT u.user_id, u.name, u.email, r.role_name
    FROM users u
    LEFT JOIN project_members pm ON u.user_id = pm.user_id
    LEFT JOIN project_roles r ON pm.role_id = r.role_id
    GROUP BY u.user_id
";
$result = $conn->query($query);

// Fetch roles for dropdown
$roles = $conn->query("SELECT role_id, role_name FROM project_roles");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management - Pani</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
    .modal-content { background:#fff; padding:20px; border-radius:10px; width:400px; position:relative; }
    .modal-content input, .modal-content select { width:100%; padding:10px; margin-bottom:10px; }
    .close { position:absolute; top:10px; right:15px; font-size:20px; cursor:pointer; }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h1 class="logo">Pani</h1>
    <nav>
      <ul>
        <li><b>Admin Panel</b></li>
        <li><a href="admin.php">Dashboard</a></li>
        <li><a href="userManagment.php">User Management</a></li>
        <li><a href="#">Project Management</a></li>
        <li><a href="TaskManagement.php">Task Management</a></li>
      </ul>
    </nav>
  </div>

  <!-- Main Section -->
  <div class="main">
    <!-- Topbar -->
    <header class="topbar">
      <div class="nav-left">
        <h2>Product Guide</h2>
        <a href="#">About</a>
      </div>
      <div class="nav-right">
        <img src="https://img.icons8.com/ios-glyphs/30/000000/user--v1.png" class="user-icon">
      </div>
    </header>

    <!-- Content Section -->
    <section class="content">
      <div class="task-header">
        <h2>User Management</h2>
        <button class="add-btn" onclick="document.getElementById('userModal').style.display='flex'">Add User</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['role_name'] ?? 'Unassigned') ?></td>
            <td>
              <a href="delete_user.php?id=<?= $row['user_id'] ?>" onclick="return confirm('Delete this user?')">
    <button class="delete-btn">Delete</button>
</a>

            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <!-- Modal Form for Add User -->
    <div class="modal" id="userModal">
      <div class="modal-content">
        <span class="close" onclick="document.getElementById('userModal').style.display='none'">&times;</span>
        <h3>Add New User</h3>
        <form method="POST">
          <input type="hidden" name="add_user" value="1">
          <input type="text" name="name" placeholder="Full Name" required>
          <input type="email" name="email" placeholder="Email" required>
          <small>Password is set as default: <b>123456</b></small><br><br>
          <input type="submit" value="Create User">
        </form>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-left">
        <h1>Pani</h1>
        <p>Pani is a project management and issue tracking website that helps teams plan, track, and manage work.</p>
        <p>Limited is a leading home service company in Bangladesh which is working for democratizing digital lifestyle across the country.</p>
      </div>
      <div class="footer-right">
        <ul><li>Home</li><li>Admin Panel</li><li>Product Guide</li><li>About</li></ul>
        <address>
          House #08, Road #10, Stadium Road,<br>
          Mirpur, Dhaka, Bangladesh<br>
          +88 017******00<br>
          pani@gmail.com
        </address>
      </div>
    </footer>
  </div>

  <script>
    window.onclick = function(event) {
      const modal = document.getElementById('userModal');
      if (event.target == modal) modal.style.display = "none";
    }
  </script>
</body>
</html>

<?php $conn->close(); ?>
