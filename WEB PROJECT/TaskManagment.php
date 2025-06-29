<?php
// DB Connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "taskmanager"; // Ensure this matches your database name

$conn = new mysqli($host, $user, $password, $dbname);

// Connection check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Add Task form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'To Do';
    $assigned_to = $_POST['assigned_to'] ?: null;

    // Insert into tasks table
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, created_by) VALUES (?, ?, ?, ?)");
    $created_by = 1; // Example admin ID
    $stmt->bind_param("sssi", $title, $description, $status, $created_by);
    $stmt->execute();
    $task_id = $conn->insert_id;

    // Insert into task_assignments table if assigned
    if (!empty($assigned_to)) {
        $stmt2 = $conn->prepare("INSERT INTO task_assignments (task_id, assigned_to) VALUES (?, ?)");
        $stmt2->bind_param("ii", $task_id, $assigned_to);
        $stmt2->execute();
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch tasks with assigned user
$query = "
    SELECT 
        t.task_id,
        t.title,
        t.status,
        u.name AS assigned_user
    FROM tasks t
    LEFT JOIN task_assignments ta ON t.task_id = ta.task_id
    LEFT JOIN users u ON ta.assigned_to = u.user_id
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pani - Task Management</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .modal {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal-content {
      background: white;
      padding: 20px;
      width: 400px;
      border-radius: 8px;
      box-shadow: 0 5px 10px rgba(0,0,0,0.3);
      position: relative;
    }

    .modal-content .close {
      position: absolute;
      right: 10px;
      top: 5px;
      cursor: pointer;
      font-size: 22px;
    }

    .modal-content form input[type="text"],
    .modal-content form textarea,
    .modal-content form select {
      width: 100%;
      margin: 5px 0 15px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
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

  <!-- Main Content -->
  <div class="main">
    <!-- Top Navigation -->
    <header class="topbar">
      <div class="nav-left">
        <h2>Product Guide</h2>
        <a href="#">About</a>
      </div>
      <div class="nav-right">
        <img src="https://img.icons8.com/ios-glyphs/30/000000/user--v1.png" alt="User Icon" class="user-icon">
      </div>
    </header>

    <!-- Task Management -->
    <section class="content">
      <div class="task-header">
        <h2>Task Management</h2>
        <button class="add-btn">Add Task</button>
      </div>

      <table>
        <thead>
          <tr>
            <th>Task</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['assigned_user'] ?? 'Unassigned') ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                  <a href="delete_task.php?id=<?= $row['task_id'] ?>" onclick="return confirm('Are you sure?')">
                    <button class="delete-btn">Delete</button>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
              <tr><td colspan="4">No tasks found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Add Task Modal -->
      <div id="addTaskModal" class="modal">
        <div class="modal-content">
          <span class="close" onclick="closeModal()">&times;</span>
          <h2>Add New Task</h2>
          <form method="POST" action="">
            <label>Task Title:</label>
            <input type="text" name="title" required>

            <label>Description:</label>
            <textarea name="description"></textarea>

            <label>Status:</label>
            <select name="status">
              <option value="To Do">To Do</option>
              <option value="In Progress">In Progress</option>
              <option value="Done">Done</option>
            </select>

            <label>Assign To:</label>
            <select name="assigned_to">
              <option value="">-- None --</option>
              <?php
              $userQuery = $conn->query("SELECT user_id, name FROM users");
              while ($user = $userQuery->fetch_assoc()) {
                echo "<option value='{$user['user_id']}'>{$user['name']}</option>";
              }
              ?>
            </select>

            <input type="submit" name="add_task" value="Add Task">
          </form>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="footer-left">
        <h1>Pani</h1>
        <p>Pani is a project management and issue tracking website that helps teams plan, track, and manage work.</p>
        <p>Limited is a leading home service company in Bangladesh which is working for democratizing digital lifestyle across the country.</p>
      </div>
      <div class="footer-right">
        <ul>
          <li>Home</li>
          <li>Admin Panel</li>
          <li>Product Guide</li>
          <li>About</li>
        </ul>
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
    function closeModal() {
      document.getElementById("addTaskModal").style.display = "none";
    }

    document.querySelector(".add-btn").addEventListener("click", () => {
      document.getElementById("addTaskModal").style.display = "flex";
    });

    window.onclick = function(event) {
      const modal = document.getElementById("addTaskModal");
      if (event.target == modal) {
        closeModal();
      }
    };
  </script>
</body>
</html>

<?php $conn->close(); ?>
