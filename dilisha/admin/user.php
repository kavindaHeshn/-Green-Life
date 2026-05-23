<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GreenLife Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=block" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @font-face {
      font-family: 'Algerian';
      src: url('https://fonts.cdnfonts.com/css/algerian') format('truetype');
      font-weight: normal;
      font-style: normal;
    }

    /* Body styling */
    body {
      background-color: #e8f5e9;
      color: #333;
      margin: 0;
      font-family: 'Poppins', Arial, sans-serif;
      font-weight: 600;
      scroll-behavior: smooth;
    }

    /* Sidebar */
    .sidebar {
      width: 16rem;
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
      color: #ffca28;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 100vh;
    }

    /* Logo styling */
    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1.5rem;
    }
    .logo img {
      width: 100%;
      max-width: 8rem;
      height: auto;
      border-radius: 0.5rem;
      border: 2px solid #ffca28;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      animation: fadeIn 0.8s ease forwards;
      opacity: 0;
    }

    /* Sidebar links */
    .nav-link {
      display: block;
      padding: 0.5rem 1rem;
      border-radius: 0.375rem;
      transition: background-color 0.3s ease, transform 0.3s ease;
      transform: translateX(-20px);
      opacity: 0;
      animation: slideIn 0.5s ease forwards;
      animation-delay: calc(0.1s * var(--index));
      color: #ffca28;
      position: relative;
      text-decoration: none;
      font-weight: 700;
    }
    .nav-link:hover {
      background-color: #b74213;
      transform: scale(1.03);
    }
    .nav-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 1rem;
      background-color: #4caf50;
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: calc(100% - 2rem);
    }
    .nav-link.active {
      background-color: #4caf50;
      font-weight: 800;
    }

    /* Logout button */
    .btn-logout {
      width: 100%;
      background-color: #4caf50;
      color: #ffffff;
      padding: 0.5rem 1rem;
      border-radius: 0.5rem;
      font-weight: 700;
      transition: background-color 0.3s ease, transform 0.2s ease;
      border: none;
      cursor: pointer;
    }
    .btn-logout:hover {
      background-color: #2e7d32;
      animation: rotate 0.7s ease-in-out both;
    }
    .btn-logout:hover span {
      animation: storm 0.7s ease-in-out both;
      animation-delay: 0.06s;
    }

    /* Main content */
    .main-content {
      flex: 1;
      padding: 2rem;
      animation: fadeIn 0.8s ease forwards;
      opacity: 0;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
    }
    .header h2 {
      font-family: 'Algerian', serif;
      font-size: 1.875rem;
      font-weight: 800;
      color: #b74213;
    }
    .header p {
      color: #333;
      font-weight: 600;
    }

    /* Flex container for layout */
    .flex-container {
      display: flex;
      min-height: 100vh;
    }

    /* User table styling */
    .container {
      max-width: 1200px;
      margin: 0 auto;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border: 2px solid #ffca28;
    }

    .container h1 {
      font-family: 'Algerian', serif;
      font-size: 2.5rem;
      color: #b74213;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .filter-container {
      margin-bottom: 20px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    select {
      padding: 8px;
      font-size: 16px;
      border-radius: 8px;
      border: 1px solid #ccc;
      background-color: #fff;
      cursor: pointer;
      transition: border-color 0.3s ease;
      font-weight: 600;
    }
    select:focus {
      outline: none;
      border-color: #ffca28;
    }

    .table-container {
      overflow-x: auto;
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      font-weight: 600;
    }

    th {
      background-color: #4caf50;
      color: white;
      font-weight: 700;
    }

    tr:nth-child(even) {
      background-color: #f8f9fa;
    }

    tr:hover {
      background-color: #e8f4e8;
    }

    .no-data {
      text-align: center;
      color: #666;
      padding: 20px;
      font-weight: 600;
    }

    .error {
      color: #dc3545;
      text-align: center;
      padding: 15px;
      background-color: #f8d7da;
      border-radius: 4px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .success {
      color: #4caf50;
      text-align: center;
      padding: 15px;
      background-color: #e8f4e8;
      border-radius: 4px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .count-table {
      max-width: 400px;
      margin: 0 auto;
    }

    .count-table th {
      background-color: #4caf50;
      font-weight: 700;
    }

    /* Action buttons */
    .action-btn {
      padding: 6px 12px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: background-color 0.3s ease, transform 0.2s ease;
      margin-right: 5px;
    }
    .edit-btn {
      background-color: #ffca28;
      color: #333;
    }
    .edit-btn:hover {
      background-color: #b74213;
      color: white;
      animation: rotate 0.7s ease-in-out both;
    }
    .delete-btn {
      background-color: #dc3545;
      color: white;
    }
    .delete-btn:hover {
      background-color: #b91c1c;
      animation: rotate 0.7s ease-in-out both;
    }

    /* Modal styling */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .modal-content {
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border: 2px solid #ffca28;
      animation: fadeIn 0.3s ease;
    }
    .modal-content h2 {
      font-family: 'Algerian', serif;
      color: #b74213;
      margin-bottom: 20px;
      font-weight: 700;
    }
    .modal-content label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
      color: #b74213;
    }
    .modal-content input, .modal-content select {
      width: 100%;
      padding: 8px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-weight: 600;
    }
    .modal-content input:focus, .modal-content select:focus {
      border-color: #ffca28;
      outline: none;
    }
    .modal-content button {
      padding: 8px 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      font-weight: 600;
    }
    .modal-content .save-btn {
      background-color: #4caf50;
      color: white;
      margin-right: 10px;
    }
    .modal-content .save-btn:hover {
      background-color: #2e7d32;
      animation: rotate 0.7s ease-in-out both;
    }
    .modal-content .cancel-btn {
      background-color: #b74213;
      color: white;
    }
    .modal-content .cancel-btn:hover {
      background-color: #8a2f0f;
      animation: rotate 0.7s ease-in-out both;
    }

    /* Animations */
    @keyframes slideIn {
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }

    @keyframes rotate {
      0% { transform: rotate(0deg) translate3d(0, 0, 0); }
      25% { transform: rotate(3deg) translate3d(0, 0, 0); }
      50% { transform: rotate(-3deg) translate3d(0, 0, 0); }
      75% { transform: rotate(1deg) translate3d(0, 0, 0); }
      100% { transform: rotate(0deg) translate3d(0, 0, 0); }
    }

    @keyframes storm {
      0% { transform: translate3d(0, 0, 0) translateZ(0); }
      25% { transform: translate3d(4px, 0, 0) translateZ(0); }
      50% { transform: translate3d(-3px, 0, 0) translateZ(0); }
      75% { transform: translate3d(2px, 0, 0) translateZ(0); }
      100% { transform: translate3d(0, 0, 0) translateZ(0); }
    }

    /* Accessibility */
    a:focus, button:focus, input:focus, select:focus {
      outline: 2px solid #ffca28;
      outline-offset: 2px;
    }

    /* Responsive design */
    @media screen and (max-width: 768px) {
      .flex-container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        min-height: auto;
      }
      th, td {
        padding: 8px;
        font-size: 14px;
      }
      .filter-container {
        flex-direction: column;
        align-items: center;
      }
      select {
        width: 100%;
        max-width: 300px;
      }
      .modal-content {
        width: 95%;
      }
    }

    @media screen and (max-width: 480px) {
      .header h2 {
        font-size: 1.8rem;
      }
      .container h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <div class="flex-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div>
        <div class="logo">
          <img src="../photos/logo.png" alt="GreenLife Wellness Center Logo" aria-label="GreenLife Wellness Center Logo">
        </div>
        <h1 class="text-2xl font-bold mb-6 text-center" style="font-family: 'Algerian', serif;">GreenLife</h1>
        <nav role="navigation" aria-label="Admin navigation">
          <ul>
            <li><a href="contactus.php" class="nav-link" style="--index: 1;" aria-label="Manage contact us">Manage Contact Us</a></li>
            <li><a href="bookings.php" class="nav-link " style="--index: 2;" aria-label="Manage bookings">Manage Bookings</a></li>
            <li><a href="user.php" class="nav-link active" style="--index: 3;" aria-label="Manage users">Manage Users</a></li>
            <li><a href="feedback.php" class="nav-link" style="--index: 4;" aria-label="Manage feedback">Manage Feedback</a></li>
          </ul>
        </nav>
      </div>
      <!-- Logout Button -->
      <div class="mt-6">
        <button id="logoutBtn" class="btn-logout" aria-label="Logout"><span>Logout</span></button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <!-- Header -->
      <div class="header">
        <div>
          <h2>Admin Dashboard</h2>
          <p>Manage all aspects of GreenLife operations.</p>
        </div>
      </div>

      <!-- Users Management Section -->
      <div class="container">
        <h1>Users List</h1>

        <!-- Role Filter -->
        <div class="filter-container">
          <form method="GET">
            <select name="role" onchange="this.form.submit()" aria-label="Filter by user role">
              <option value="">All Roles</option>
              <option value="customer" <?php echo (isset($_GET['role']) && $_GET['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
              <option value="staff" <?php echo (isset($_GET['role']) && $_GET['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
              <option value="admin" <?php echo (isset($_GET['role']) && $_GET['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
          </form>
        </div>

        <?php
        $host = "localhost";
        $user = "root";
        $password = "";
        $dbname = "GreenLife";

        try {
          $conn = new mysqli($host, $user, $password, $dbname);
          if ($conn->connect_error) {
            die('<div class="error">Connection failed: ' . $conn->connect_error . '</div>');
          }
          $conn->set_charset("utf8mb4");

          // Handle delete request
          if (isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];
            $delete_sql = "DELETE FROM users WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $delete_id);
            if ($delete_stmt->execute()) {
              echo '<div class="success">User deleted successfully.</div>';
            } else {
              echo '<div class="error">Error deleting user: ' . $conn->error . '</div>';
            }
            $delete_stmt->close();
          }

          // Handle edit request
          if (isset($_POST['edit_user'])) {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $gender = $_POST['gender'];
            $role = $_POST['role'];

            $update_sql = "UPDATE users SET username = ?, email = ?, phone = ?, gender = ?, role = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssi", $username, $email, $phone, $gender, $role, $id);
            if ($update_stmt->execute()) {
              echo '<div class="success">User updated successfully.</div>';
            } else {
              echo '<div class="error">Error updating user: ' . $conn->error . '</div>';
            }
            $update_stmt->close();
          }

          // Get selected role from GET parameter
          $role = isset($_GET['role']) ? $_GET['role'] : '';
          $whereClause = '';
          $params = [];
          $types = '';

          // Prepare query based on role filter
          if ($role && in_array($role, ['customer', 'staff', 'admin'])) {
            $whereClause = 'WHERE role = ?';
            $params[] = $role;
            $types = 's';
          }

          // Query to fetch users
          $sql = "SELECT id, username, email, phone, gender, created_at, role FROM users $whereClause ORDER BY created_at DESC";
          $stmt = $conn->prepare($sql);

          if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
          }

          $stmt->execute();
          $result = $stmt->get_result();

          if ($result) {
            if ($role == 'customer') {
              echo '<h2>Customers List</h2>';
            } else {
              echo '<h2>' . ($role ? ucfirst($role) . ' List' : 'All Users') . '</h2>';
            }

            if ($result->num_rows > 0) {
              echo '<div class="table-container">';
              echo '<table>';
              echo '<tr>';
              echo '<th>ID</th>';
              echo '<th>Username</th>';
              echo '<th>Email</th>';
              echo '<th>Phone</th>';
              echo '<th>Gender</th>';
              echo '<th>Created At</th>';
              echo '<th>Role</th>';
              echo '<th>Actions</th>';
              echo '</tr>';

              while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                echo '<td>' . htmlspecialchars($row['role']) . '</td>';
                echo '<td>';
                echo '<button class="action-btn edit-btn" onclick="openEditModal(' . htmlspecialchars(json_encode($row)) . ')" aria-label="Edit user ' . htmlspecialchars($row['username']) . '"><span>Edit</span></button>';
                echo '<button class="action-btn delete-btn" onclick="deleteUser(' . htmlspecialchars($row['id']) . ')" aria-label="Delete user ' . htmlspecialchars($row['username']) . '"><span>Delete</span></button>';
                echo '</td>';
                echo '</tr>';
              }
              echo '</table>';
              echo '</div>';
            } else {
              echo '<p class="no-data">No ' . ($role ? strtolower($role) . 's' : 'users') . ' found in the database.</p>';
            }
            $result->free();
          } else {
            echo '<div class="error">Error executing query: ' . $conn->error . '</div>';
          }

          // Query to get role counts
          $count_sql = "SELECT role, COUNT(*) as count FROM users GROUP BY role";
          $count_result = $conn->query($count_sql);

          if ($count_result) {
            $counts = [
              'customer' => 0,
              'staff' => 0,
              'admin' => 0
            ];

            while ($row = $count_result->fetch_assoc()) {
              $counts[$row['role']] = $row['count'];
            }

            echo '<h2>User Role Counts</h2>';
            echo '<div class="table-container">';
            echo '<table class="count-table">';
            echo '<tr><th>Role</th><th>Count</th></tr>';
            echo '<tr><td>Customer</td><td>' . $counts['customer'] . '</td></tr>';
            echo '<tr><td>Staff</td><td>' . $counts['staff'] . '</td></tr>';
            echo '<tr><td>Admin</td><td>' . $counts['admin'] . '</td></tr>';
            echo '</table>';
            echo '</div>';

            $count_result->free();
          } else {
            echo '<div class="error">Error fetching role counts: ' . $conn->error . '</div>';
          }

          $stmt->close();
          $conn->close();
        } catch (Exception $e) {
          echo '<div class="error">Database connection error: ' . $e->getMessage . '</div>';
        }
        ?>
      </div>

      <!-- Edit Modal -->
      <div id="editModal" class="modal">
        <div class="modal-content">
          <h2>Edit User</h2>
          <form id="editForm" method="POST">
            <input type="hidden" name="id" id="editId">
            <label for="editUsername"><i class="fas fa-user"></i> Username</label>
            <input type="text" name="username" id="editUsername" required aria-label="Username">
            <label for="editEmail"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" name="email" id="editEmail" required aria-label="Email">
            <label for="editPhone"><i class="fas fa-phone"></i> Phone</label>
            <input type="text" name="phone" id="editPhone" required aria-label="Phone">
            <label for="editGender"><i class="fas fa-venus-mars"></i> Gender</label>
            <select name="gender" id="editGender" required aria-label="Gender">
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <label for="editRole"><i class="fas fa-user-tag"></i> Role</label>
            <select name="role" id="editRole" required aria-label="Role">
              <option value="customer">Customer</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
            <button type="submit" name="edit_user" class="save-btn" aria-label="Save changes"><span>Save</span></button>
            <button type="button" class="cancel-btn" onclick="closeEditModal()" aria-label="Cancel"><span>Cancel</span></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', () => {
      if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'login.html';
      }
    });

    // Active link highlighting
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', () => {
        document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
        link.classList.add('active');
      });
    });

    // Edit modal functions
    function openEditModal(user) {
      document.getElementById('editId').value = user.id;
      document.getElementById('editUsername').value = user.username;
      document.getElementById('editEmail').value = user.email;
      document.getElementById('editPhone').value = user.phone;
      document.getElementById('editGender').value = user.gender;
      document.getElementById('editRole').value = user.role;
      document.getElementById('editModal').style.display = 'flex';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    // Delete user function
    function deleteUser(id) {
      if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = '?delete_id=' + id + '<?php echo isset($_GET['role']) ? "&role=" . htmlspecialchars($_GET['role']) : ""; ?>';
      }
    }
  </script>
</body>
</html>